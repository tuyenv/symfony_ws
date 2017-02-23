<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\BaseController;

class DefaultController extends BaseController
{
    /**
     * This action for quick test functionally
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $apiKey = $request->headers->get('Authorization');
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findUserByApiKey($apiKey);
        return $this->renderJson($user);
    }
}
