<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

class ResponseCache implements MiddlewareInterface
{
    /**
     * @var string|null
     */
    private $cacheFilesPath = null;

    /**
     * @var int
     */
    private $maxFileAgeInSeconds = 60;

    public function __construct(string $cacheFilesPath, ?int $maxFileAgeInSeconds = null)
    {
        $this->cacheFilesPath = $cacheFilesPath;

        if (null !== $maxFileAgeInSeconds) {
            $this->maxFileAgeInSeconds = $maxFileAgeInSeconds;   
        }
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $fileName = md5((string)$request->getUri()) . '.cache';
        $cacheFilePath = $this->cacheFilesPath . $fileName;      

        $fileDateNeedsToBeNewerThan = time() - $this->maxFileAgeInSeconds;

        // http GET requests only are cached
        if ('GET' !== $request->getMethod()) {
            return $delegate->process($request);
        }

        // Home-made implementation to keep things simple - NOT suggested for production
        // Take a look at https://github.com/middlewares/cache
        if (
            !file_exists($cacheFilePath) || 
            filemtime($cacheFilePath) < $fileDateNeedsToBeNewerThan
        ) {
            // Cache file is created
            $response = $delegate->process($request);
            file_put_contents($cacheFilePath, $response->getBody());
        } else {
            // No body is returned
            if ($request->getHeaderLine('If-Modified-Since')) {
                return (new EmptyResponse())
                    ->withStatus(304)
                    ->withHeaders([
                        'Last-Modified' => date('D, d M Y H:i:s T', filemtime($cacheFilePath))
                    ]);
            }

            $contents = json_decode(file_get_contents($cacheFilePath));
            $response = new JsonResponse($contents);
        }

        return $response;
    }
}
