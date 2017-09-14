<?php

namespace Jaimongo\RabbitMQBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $iMessage = $request->get("message");
        if (is_null($iMessage)) {
            $iMessage = "There's no message";
        }

        $message = array(
            "id"    => uniqid("id_"),
            "msg"   => $iMessage
        );
        $pubService = $this->get("rabbit_publisher");
        $pubService->pubMessage(serialize($message));

        return $this->render('RabbitMQBundle:Default:index.html.twig');
    }
}
