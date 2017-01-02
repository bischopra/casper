<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\EventUtils;

class EventController extends Controller
{
    /**
     * @Route("/event/{id}", name="event", requirements={"id": "\d+"})
     */
    public function eventAction(Request $request, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        if ($id > 0)
        {
            $event = $em->getRepository('AppBundle:Event')->find($id);
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $event = $form->getData('appbundle_event');
            $event->setUser($this->getUser());
            $event->setAlias(EventUtils::makeAlias($event->getName(), $event->getId()));

            $em->persist($event);
            $em->flush();

            $event->setAlias(EventUtils::makeAlias($event->getName(), $event->getId()));
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Event:event.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/{alias}", name="event_details")
     */
    public function eventDetailsAction($alias)
    {
        $id = EventUtils::decodeAliasToID($alias);
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        return $this->render('AppBundle:Event:eventDetails.html.twig', array(
            'event' => $event,
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
