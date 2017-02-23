<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class WsListener
{
    const AUTH_NOT_EXIST = 1;
    const AUTH_NOT_VALID = 2;
    const AUTH_NOT_PERMISSION = 3;
    const AUTH_SUCCESS = 4;

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Write log
     *
     * @param $event
     * @param $responseStatus
     * @param int $uid
     * @return mixed
     */
    public function writeLog($event, $responseStatus, $uid = 0)
    {
        $request        = $event->getRequest();
        $service        = $request->get('_route');
        $method         = $request->getMethod();
        $endPoint       = $request->getUri();
        $queryString    = $request->getQueryString();
        $apiKey         = $request->headers->get('Authorization', '');

        try {
            return $this->em->getRepository('AppBundle:Log')->writeLog(
                $service,
                $method,
                $endPoint,
                $queryString,
                $responseStatus,
                $apiKey,
                $uid);
        } catch (\Exception $e) {
            // write log error here
        }
    }

    /**
     * Authenticate and Authorize
     * @return int
     */
    public function validAuthenticate($event)
    {
        try {
            $request = $event->getRequest();
            if (!$request->headers->has('Authorization')) {
                return self::AUTH_NOT_EXIST;
            }

            // implement store user auth in Redis and Json Web Token (JWT) so no need to get database for every request

            // check authenticate
            $accessToken = $request->headers->get('Authorization');
            $authenticate = $this->em->getRepository('AppBundle:User')->findUserByApiKey($accessToken);
            if (empty($authenticate)) {
                return self::AUTH_NOT_VALID;
            }

            // check permission - we can check permission here for routing with param [uid]
            // uncomment this block in some specific case example (single page app)
            /*
            $uid = $authenticate[0]['id'];
            $event->getRequest()->attributes->set('userId', $uid);
            if ($request->query->has('uid') && $uid != $request->query->get('uid')) {
                return self::AUTH_NOT_PERMISSION;
            }
            */

            return self::AUTH_SUCCESS;
        } catch (\Exception $e) {
            // write log error here

        }
    }

    /**
     * onKernelController listener
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        $event->getRequest()->attributes->set('authenticate', $this->validAuthenticate($event));
    }

    /**
     * onKernelResponse listener
     *
     * @param FilterResponseEvent $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->getRequest()->attributes->has('authenticate')) {
            return false;
        }

        $validAuth = $event->getRequest()->attributes->get('authenticate');
        $response = $event->getResponse();
        $uid = 0;

        if ($validAuth !== self::AUTH_SUCCESS) {
            switch ($validAuth) {
                case self::AUTH_NOT_EXIST:
                    $statusCode = 400;
                    $errorMessage = '400 Bad Request - No API Key';
                    break;
                case self::AUTH_NOT_VALID:
                    $statusCode = 401;
                    $errorMessage = '401 Unauthorized - Wrong API Key';
                    break;
                case self::AUTH_NOT_PERMISSION:
                    $statusCode = 403;
                    $errorMessage = '403 Forbidden - Permission';
                    break;

                default:
                    $statusCode = $response->getStatusCode();
                    $errorMessage = 'Something went wrong';
                    break;
            }

            $response->setContent(json_encode(array('error' => $errorMessage)));
        } else {
            $uid = $event->getRequest()->attributes->get('userId');
            $statusCode = $response->getStatusCode();
        }

        // write log
        $this->writeLog($event, $statusCode, $uid);

        // response
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($statusCode);
        return $response;
    }
}