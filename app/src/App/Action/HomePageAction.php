<?php

declare(strict_types=1);

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageAction implements ServerMiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        return new HtmlResponse($this->template->render('app::home-page'));
    }
}
