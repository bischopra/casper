<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event/{id}", name="event", requirements={"id": "\d+"})
     */
    public function eventAction(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $event->setEventDate(new \DateTime());
        $event->setApplyEndDate(new \DateTime());
        if ($id > 0)
        {
            $event = $em->getRepository('AppBundle:Event')->find($id);
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $event = $form->getData('appbundle_event');

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('app_event_events');
        }

        return $this->render('AppBundle:Event:event.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/events")
     */
    public function eventsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findAll();
        return $this->render('AppBundle:Event:events.html.twig', array(
            'evetns' => $events,
        ));
    }

}
