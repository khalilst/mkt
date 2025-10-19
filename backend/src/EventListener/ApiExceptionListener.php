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

        $message = match ($statusCode) {
            404 => 'Resource Not Found!',
            default => $exception->getMessage() ?: 'Server error!',
        };

        $response = new JsonResponse(
            ['error' => $message],
            $statusCode,
            ['Content-Type' => 'application/json'],
        );

        $event->setResponse($response);
    }
}
