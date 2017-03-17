<?php
namespace AppBundle\Controller;

use AppBundle\BaseController;
use AppBundle\Entity\Hero;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class HeroController extends BaseController
{
    /**
     * @Route("/heroes", name="hero_list")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $heroList = $this->getDoctrine()->getRepository('AppBundle:Hero')->getAll();
        if (empty($heroList)) {
            return $this->renderError422('Non Existing Hero');
        }

        return $this->renderJson($heroList);
    }

    /**
     * @Route("/heroes", name="hero_add")
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        if (!$request->request->has('name') || !$request->request->get('name')) {
            return $this->renderError422('Not Valid Data');
        }

        $name = $request->request->get('name');
        $heroDetail = $this->getDoctrine()->getRepository('AppBundle:Hero')->createHero($name);
        if (!$heroDetail instanceof Hero) {
            return $this->renderError422('Something went wrong');
        }

        return $this->renderJson(array('status' => 1));
    }

    /**
     * @Route("/heroes/{id}", name="hero_update", requirements={"id": "\d+"})
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $heroDetail = $this->getDoctrine()->getRepository('AppBundle:Hero')->findHeroById($id);
        if (empty($heroDetail)) {
            return $this->renderError422('Non Existing Hero');
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name']) || $data['name'] == '') {
            return $this->renderError422('Not Valid Data');
        }

        $this->getDoctrine()->getRepository('AppBundle:Hero')->updateHero($id, $data['name']);
        return $this->renderJson(array('status' => 1));
    }

    /**
     * @Route("/heroes/{id}", name="hero_delete", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $heroDetail = $this->getDoctrine()->getRepository('AppBundle:Hero')->findHeroById($id);
        if (empty($heroDetail)) {
            return $this->renderError422('Non Existing Hero');
        }

        $this->getDoctrine()->getRepository('AppBundle:Hero')->deleteHero($id);
        return $this->renderJson(array('status' => 1));
    }
}
