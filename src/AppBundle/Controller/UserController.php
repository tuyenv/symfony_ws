<?php
namespace AppBundle\Controller;

use AppBundle\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/user/{id}", name="user_detail", requirements={"id": "\d+"})
     * @Method({"GET"})
     */
    public function indexAction(Request $request, $id)
    {
        $accessToken = $request->headers->get('Authorization');
        $userDetail = $this->getDoctrine()->getRepository('AppBundle:User')->findUserByUid($id);
        if (empty($userDetail)) {
            return $this->renderError422('Non Existing User');
        }

        if ($accessToken != $userDetail[0]['apiKey']) {
            return $this->renderError403();
        }

        $jsonData = $this->getDoctrine()->getRepository('AppBundle:User')->getJsonData($userDetail[0]);
        return $this->renderJson($jsonData);
    }
}
