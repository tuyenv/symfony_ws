<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class ExceptionListener extends WsListener
{
    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        // Customize your response object to display the exception details
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $responseStatus = $exception->getStatusCode();
            $response->setStatusCode($exception->getStatusCode());

            // Write Log API
            $this->writeLog($event, $responseStatus);
        } else {
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

            // Write Log Exception
            $exception = $event->getException();
            $message = sprintf(
                '%s: %s (uncaught exception) at %s line %s',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
            $this->logger->error($message, array('exception' => $exception));
        }

        $message = Response::$statusTexts[$responseStatus];
        $response->setContent(json_encode(array('error' => $message)));

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}