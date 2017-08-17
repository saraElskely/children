<?php

namespace TimeBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use TimeBundle\Exception\TimeBundleException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of ExceptionListener
 *
 * @author saraelsayed
 */
class ExceptionListener {

    private $twig;

    function __construct($twig) {
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        $response = new Response();
        $message = [
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]
        ];
        $response->setContent(json_encode($message));

        if ($exception instanceof TimeBundleException) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $requestRoute = explode('_', $event->getRequest()->get('_route'), 2);
        if ($requestRoute[0] === 'api' || $event->getRequest()->isXmlHttpRequest()) {
            $event->setResponse($response);
        } else {
            $event->setResponse(new Response(
                    $this->twig->render('TimeBundle:exception:error500.html.twig', $message)
            ));
        }
    }

}
