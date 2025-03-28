<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Comment;
use app\helpers\JWTHandler;

class CommentApiController
{
  private $commentModel;
  private $jwtHandler;

  public function __construct()
  {
    $this->commentModel = new Comment();
    $this->jwtHandler = new JWTHandler();
  }

  private function authenticate(): ?array
  {
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
      $authHeader = $headers['Authorization'];
      $arr = explode(" ", $authHeader);
      $jwt = $arr[1] ?? null;

      if ($jwt) {
        $decoded = $this->jwtHandler->decode($jwt);
        return json_decode(json_encode($decoded), true);
      }
    }
    return null;
  }

  /**
   * @OA\Post(
   *     path="/comments/{productId}",
   *     summary="Comment a product",
   *     tags={"Comment"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="productId",
   *         in="path",
   *         required=true,
   *         description="The ID of the product",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         description="Comment information for product",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="comment", type="string", description="Comment content", example="This product is very good!"),
   *             @OA\Property(property="rating", type="integer", description="Star rating", example=5)
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Comment added successfully",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Comment added successfully")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to comment")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Invalid request",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Comment content cannot be empty")
   *         )
   *     )
   * )
   */
  public function store($productId)
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $data = json_decode(file_get_contents("php://input"), true);

      $customerId = $userCustomer['user_customer']['id'];
      $comment = $data['comment'] ?? '';
      $rating = $data['rating'] ?? 5;

      if (empty($comment)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Comment content cannot be empty']);
        return;
      }

      // Add the comment to the database
      $this->commentModel->addComment($productId, $customerId, $comment, $rating);

      // Return success response
      echo json_encode(['message' => 'Comment added successfully']);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to comment']);
      return;
    }
  }

  /**
   * @OA\Get(
   *     path="/comments/{productId}",
   *     summary="Get product's comments",
   *     tags={"Comment"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="productId",
   *         in="path",
   *         required=true,
   *         description="The ID of the product",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Get comments successfully"
   *     )
   * )
   */
  public function index($productId)
  {
    $comments = $this->commentModel->getCommentsByProduct($productId);

    // Return comments as JSON
    echo json_encode($comments);
  }

  /**
   * @OA\Get(
   *     path="/comments/{productId}/averageRating",
   *     summary="Get product's average rating",
   *     tags={"Comment"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="productId",
   *         in="path",
   *         required=true,
   *         description="The ID of the product",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Get average rating successfully"
   *     )
   * )
   */
  public function getAverageRating($productId)
  {
    $averageRating = $this->commentModel->getAverageRating($productId);

    // Return average rating as JSON
    echo json_encode($averageRating);
  }
}
