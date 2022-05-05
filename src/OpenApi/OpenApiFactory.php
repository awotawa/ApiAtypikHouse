<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{

  public function __construct(
    private OpenApiFactoryInterface $decorated
  ) {
  }

  public function __invoke(array $context = []): OpenApi
  {
    $openApi = $this->decorated->__invoke($context);
    /** @var PathIem $path */
    foreach ($openApi->getPaths()->getPaths() as $key => $path) {
      if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
        $openApi->getPaths()->addPath($key, $path->withGet(null));
      }
    }

    $schemas = $openApi->getComponents()->getSecuritySchemes();
    $schemas['bearerAuth'] = new \ArrayObject([
      'type' => 'http',
      'scheme' => 'bearer',
      'bearerFormat' => 'JWT'
    ]);

    $schemas = $openApi->getComponents()->getSchemas();
    $schemas['Credentials'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'username' => [
          'type' => 'string',
          'example' => 'toto@gmail.com',
        ],
        'password' => [
          'type' => 'string',
          'example' => '1234',
        ],
      ],
    ]);
    $schemas['Token'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'token' => [
          'type' => 'string',
          'readOnly' => true,
        ]
      ],
    ]);

    $pathItem = new PathItem(
      // ref: 'JWT Token',
      post: new Operation(
        operationId: 'postApiLogin',
        tags: ['Token'],
        requestBody: new RequestBody(
          content: new \ArrayObject([
            'application/json' => [
              'schema' => [
                '$ref' => '#/components/schemas/Credentials',
              ],
            ],
          ])
        ),
        responses: [
          '200' => [
            'description' => 'Token JWT',
            'content' => [
              'application/json' => [
                'schema' => [
                  '$ref' => '#/components/schemas/Token',
                ],
              ],
            ],
          ],
        ]
      ),
    );
    $openApi->getPaths()->addPath('/api/login', $pathItem);

    return $openApi;
  }
}
