<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\JsonResponse;
use DomainException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DomainExceptionHandler implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage(), [
                'exception' => $exception,
                'url' => (string)$request->getUri()
            ]);
            return new JsonResponse([
                'message' => $this->translator->trans($exception->getMessage(), [], 'exceptions')
            ], 409);
        }
    }
}
