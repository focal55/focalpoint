<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ScheduleController extends Controller
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function indexAction(Request $request)
    {
        return $this->render('schedule/index.html.twig');
    }
}
