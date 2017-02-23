<?php
namespace AppBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{

    const ERROR_NOT_FOUND = 'Not Found';
    const ERROR_PERMISSION_DENIED = 'Permission Denied';

    /**
     * @param $jsonData
     * @param int $status
     * @return Response
     */
    protected function renderJson($jsonData, $status = 200)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($status);
        $response->setContent($this->json($jsonData));

        return $response;
    }

    /**
     * @param $error
     * @param int $status
     * @return Response
     */
    protected function renderError($error, $status = 404)
    {
        return $this->renderJson(array('error' => $error), $status);
    }

    protected function renderError403()
    {
        return $this->renderError(self::ERROR_PERMISSION_DENIED, 403);
    }

    protected function renderError404($status = 404)
    {
        return $this->renderError(self::ERROR_NOT_FOUND, $status);
    }

    protected function renderError422($error)
    {
        return $this->renderError($error, 422);
    }
}