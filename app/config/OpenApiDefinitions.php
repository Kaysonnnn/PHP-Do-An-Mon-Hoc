<?php
namespace app\config;

require __DIR__ . '/../../vendor/autoload.php';

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Customer API Document",
 *         version="v1",
 *         description="Server API for customer",
 *         @OA\Contact(
 *             email="nkduy.dev@gmail.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:81/doan/api",
 *         description="Production server"
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="bearerAuth",
 *             type="http",
 *             scheme="bearer",
 *             bearerFormat="JWT",
 *             description="Enter your JWT token in the format: Bearer {token}"
 *         )
 *     )
 * )
 */
class OpenApiDefinitions
{}
