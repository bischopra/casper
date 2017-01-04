<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\AliasUtils;
use Symfony\Component\HttpFoundation\JsonResponse;

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

            $em->persist($event);
            $em->flush();

            $event->setAlias(AliasUtils::makeAlias($event->getName(), $event->getId()));
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Event:event.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/{alias}", name="event_attend")
     * @Method({"POST"})
     */
    public function eventAttendAction($alias)
    {
        $id = AliasUtils::decodeAliasToID($alias);
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        $user = $this->getUser();

        $status = $em->getRepository('AppBundle:Participation')->attend($event, $user);

        return new JsonResponse(['status' => $status, 'name' => $user->getNick()]);
    }

    /**
     * @Route("/event/{alias}", name="event_details")
     * @Method({"GET"})
     */
    public function eventDetailsAction($alias)
    {
        $id = AliasUtils::decodeAliasToID($alias);
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        $participation = $em->getRepository('AppBundle:Participation')->getParticipation($this->getUser(), $event);
        $isOwner = 0;
        if ($event->getUser() == $this->getUser())
        {
            $isOwner = 1;
        }
        return $this->render('AppBundle:Event:eventDetails.html.twig', array(
            'event' => $event,
            'participation' => $participation,
            'isOwner' => $isOwner,
        ));
    }

    /**
     * @Route("/events")
     */
    public function eventsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Participation')->getUserAttendingEvents($this->getUser());
        return $this->render('AppBundle:Event:events.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * @Route("/myevents", name="my_events")
     */
    public function myEventsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->getUserEvents($this->getUser());
        return $this->render('AppBundle:Event:myEvetns.html.twig', array(
            'events' => $events,
        ));
    }
}
