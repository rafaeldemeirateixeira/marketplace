<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        // Get incoming request
        $request   = $event->getRequest();

        // Check if it is a rest api request
        if ('application/json' === $request->headers->get('Content-Type')) {

            // Customize your response object to display the exception details
            $data = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'errors' => $exception->getTrace()
            ];
            $response = new JsonResponse($data);


            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
                $data['code'] = $exception->getStatusCode();
                $response->setData($data);
            } elseif (in_array($exception->getCode(), array_keys(Response::$statusTexts))) {
                $response->setStatusCode($exception->getCode());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $response->headers->set('Content-Type', 'text/json; charset=UTF-8');

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }
}
