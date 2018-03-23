<?php

namespace webignition\WebResource\TestingTools;

use Mockery;
use Mockery\Mock;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseFactory
{
    /**
     * @param string $fixtureName
     * @param string $contentType
     *
     * @return Mock|ResponseInterface
     */
    public static function createFromFixture($fixtureName, $contentType)
    {
        return self::create($contentType, FixtureLoader::load($fixtureName));
    }

    /**
     * @param string $contentType
     * @param string $content
     * @param StreamInterface|null $bodyStream
     *
     * @return Mock|ResponseInterface
     */
    public static function create($contentType, $content = '', $bodyStream = null)
    {
        /* @var ResponseInterface|Mock $response */
        $response = Mockery::mock(ResponseInterface::class);

        $response
            ->shouldReceive('getHeader')
            ->with('content-type')
            ->andReturn([
                $contentType,
            ]);

        if (empty($bodyStream)) {
            /* @var StreamInterface|Mock $bodyStream */
            $bodyStream = Mockery::mock(StreamInterface::class);
            $bodyStream
                ->shouldReceive('__toString')
                ->andReturn($content);
        }

        $response
            ->shouldReceive('getBody')
            ->andReturn($bodyStream);

        return $response;
    }
}
