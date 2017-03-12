<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Form\EventFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class ScheduleController extends Controller
{

    /**
     * @Route("/schedule", name="admin_schedule_dashboard")
     */
    public function indexAction(Request $request) {
        return $this->render('admin/schedule/dashboard.html.twig');
    }

    /**
     * @Route("/schedule/new", name="admin_schedule_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(EventFormType::class);

        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Project "%s" has been created.', $entity->getName())
            );

            return $this->redirectToRoute('admin_schedule_dashboard');
        }

        return $this->render('admin/schedule/new.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
