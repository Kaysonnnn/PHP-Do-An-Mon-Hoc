<?php
namespace app\controllers;

require __DIR__ . '/../../vendor/autoload.php';

use OpenApi\Generator;

class SwaggerController
{
  public function docs()
  {
    // Quét các file PHP chứa các chú thích OpenAPI Swagger
    $openapi = Generator::scan([
      __DIR__ . '/../config/OpenApiDefinitions.php',
      __DIR__ . '/./api',
    ]);

    // Đảm bảo trả về kết quả dưới dạng JSON
    header('Content-Type: application/json');
    echo $openapi->toJson();
  }
}