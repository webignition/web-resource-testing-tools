<?php

namespace webignition\WebResource\TestingTools;

use Mockery;
use Mockery\Mock;
use Mockery\MockInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseFactory
{
    const CONTENT_TYPE_ATOM = 'application/atom+xml';
    const CONTENT_TYPE_RSS = 'application/rss+xml';
    const CONTENT_TYPE_XML = 'text/xml';
    const CONTENT_TYPE_TXT = 'text/plain';
    const CONTENT_TYPE_HTML = 'text/html';

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
        /* @var ResponseInterface|MockInterface $response */
        $response = Mockery::mock(ResponseInterface::class);

        $response
            ->shouldReceive('getHeader')
            ->with('content-type')
            ->andReturn([
                $contentType,
            ]);

        $response
            ->shouldReceive('getHeaderLine')
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
