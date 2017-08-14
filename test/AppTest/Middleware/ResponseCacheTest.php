<?php

declare(strict_types=1);

namespace AppTest\Middleware;

use App\Middleware\ResponseCache;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Stratigility\Delegate\CallableDelegateDecorator;

final class ResponseCacheTest extends TestCase
{
    /**
     * @var string
     */
    private $testCacheFolder;

    /**
     * @var ResponseCache
     */
    private $responseCacheMiddleware;

    /**
     * @var string
     */
    private $responseContent;

    /**
    * @var bool
    */
    private $ifModifiedSinceHeaderSet;

    public function setUp()
    {
        $this->responseContent = 'test';
        $this->ifModifiedSinceHeaderSet = false;
        
        $this->testCacheFolder = '/tmp/response-cache-test-'. uniqid() . '/';
        mkdir($this->testCacheFolder);
        
        $this->responseCacheMiddleware = new ResponseCache($this->testCacheFolder, 2);
    }

    public function testNewResponse()
     {
        $response = $this->processMiddleware();

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test']), (string) $response->getBody());
    }
   
    public function testCachedFile()
    {
        $response1 = $this->processMiddleware();
        self::assertInstanceOf(JsonResponse::class, $response1);
        self::assertSame(200, $response1->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test']), (string) $response1->getBody());

        // Content changes
        $this->responseContent = 'test2';

        // Cached result is returned
        $response2 = $this->processMiddleware();
        self::assertInstanceOf(JsonResponse::class, $response2);
        self::assertSame(200, $response2->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test']), (string) $response2->getBody());

        // Cache expires
        sleep(4);

        // Fresh result is returned
        $response3 = $this->processMiddleware();
        self::assertInstanceOf(JsonResponse::class, $response3);
        self::assertSame(200, $response3->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test2']), (string) $response3->getBody());

    }
    
    public function testNotModifiedHeader()
    {       
        $this->ifModifiedSinceHeaderSet = true;

        $response1 = $this->processMiddleware(true);
        self::assertInstanceOf(Response::class, $response1);
        self::assertSame(200, $response1->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test']), (string) $response1->getBody());

        // Content changes
        $this->responseContent = 'test2';

        // 204 header and empty body are returned
        $response2 = $this->processMiddleware(true);
        self::assertInstanceOf(Response\EmptyResponse::class, $response2);
        self::assertSame(204, $response2->getStatusCode());

        // Cache expires
        sleep(4);

        // Fresh result is returned
        $response3 = $this->processMiddleware();
        self::assertInstanceOf(JsonResponse::class, $response3);
        self::assertSame(200, $response3->getStatusCode());
        $this->assertSame(json_encode(['content' => 'test2']), (string) $response3->getBody());
    }

    public function tearDown()
    {
        array_map('unlink', glob($this->testCacheFolder . '*.cache'));
        rmdir($this->testCacheFolder);
        \Mockery::close();
    }

    private function processMiddleware() 
    {
        $request = $this->buildRequest();
        
        if ($this->ifModifiedSinceHeaderSet) {
            $request = $request->withAddedHeader('If-Modified-Since', date('D, d M Y H:i:s T'));
        }

        return $this->responseCacheMiddleware->process(
            $request,
            new CallableDelegateDecorator(
                function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $response;
                },
                new JsonResponse(['content' => $this->responseContent], 200)
            )
        );
    }

    private function buildRequest() 
    {
        $request = (new ServerRequest())->withMethod('GET')->withUri(new \Zend\Diactoros\Uri('http://www.some-test-uri.it'));
        return $request;
    }

}
