<?php

declare(strict_types=1);

namespace App\Presentation\Handlers;

use App\Presentation\Actions\Protocols\ActionError;
use App\Presentation\Actions\Protocols\ActionPayload;
use App\Presentation\Actions\Protocols\HttpErrors\UnprocessableEntityException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * {@inheritdoc}
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ActionError(
            ActionError::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        $this->logError($exception->getTraceAsString());
        $this->logError($exception->getMessage());

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            $this->logError($exception->getMessage());

            $errorType = match (true) {
                $exception instanceof HttpNotFoundException => ActionError::RESOURCE_NOT_FOUND,
                $exception instanceof HttpMethodNotAllowedException => ActionError::NOT_ALLOWED,
                $exception instanceof HttpUnauthorizedException => ActionError::UNAUTHENTICATED,
                $exception instanceof UnprocessableEntityException => ActionError::UNPROCESSABLE_ENTITY,
                $exception instanceof HttpForbiddenException => ActionError::INSUFFICIENT_PRIVILEGES,
                $exception instanceof HttpBadRequestException => ActionError::BAD_REQUEST,
                $exception instanceof HttpNotImplementedException => ActionError::NOT_IMPLEMENTED,
                default => ActionError::SERVER_ERROR,
            };

            $error->setType($errorType);
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $error->setDescription($exception->getMessage());
        }

        $payload = new ActionPayload($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
