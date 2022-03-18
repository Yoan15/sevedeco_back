<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {

    }

    public function __invoke(array $context = []): OpenApi
    {
        $openapi = $this->decorated->__invoke($context);
        /** @var PathItem $path */
        foreach ($openapi->getPaths()->getPaths() as $key => $path) {
            if($path->getGet() && $path->getGet()->getSummary() === 'hidden')
            {
                $openapi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        $openapi->getPaths()->addPath('/ping', new PathItem(null, 'Ping', null, new Operation('ping-id', [], [], 'Répond')));
        return $openapi;
    }
}