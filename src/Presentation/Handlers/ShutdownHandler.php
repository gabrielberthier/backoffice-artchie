<?php

declare(strict_types=1);

namespace App\Presentation\Handlers;

use App\Presentation\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Interfaces\ErrorHandlerInterface;

class ShutdownHandler
{
    public function __construct(
        private Request $request,
        private ErrorHandlerInterface $errorHandler,
        private bool $displayErrorDetails
    ) {
    }

    public function __invoke()
    {
        $error = error_get_last();
        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = sprintf('FATAL ERROR: %s. ', $errorMessage);
                        $message .= sprintf(' on line %d in file %s.', $errorLine, $errorFile);

                        break;

                    case E_USER_WARNING:
                        $message = sprintf('WARNING: %s', $errorMessage);

                        break;

                    case E_USER_NOTICE:
                        $message = sprintf('NOTICE: %s', $errorMessage);

                        break;

                    default:
                        $message = sprintf('ERROR: %s', $errorMessage);
                        $message .= sprintf(' on line %d in file %s.', $errorLine, $errorFile);

                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke($this->request, $exception, $this->displayErrorDetails, false, false);

            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        }
    }
}