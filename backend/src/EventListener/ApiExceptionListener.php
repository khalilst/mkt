<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ApiExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        // Handle only API routes
        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $exception = $event->getThrowable();

        // Default values
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        [$message, $errors] = match ($statusCode) {
            404 => ['Resource Not Found!', null],
            422 => ['Validation Error!', explode("\n", $exception->getMessage())],
            default => [$exception->getMessage() ?: 'Server error!', null],
        };

        $response = new JsonResponse(
            compact('message', 'errors'),
            $statusCode,
            ['Content-Type' => 'application/json'],
        );

        $event->setResponse($response);
    }
}
