<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Event;
use AppBundle\Entity\Participation;
use AppBundle\Entity\User;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Service\Alias;
use AppBundle\Service\EventService;
use AppBundle\Service\Invitation;

class EventController extends Controller
{
    /**
     * @Route("/event/{id}", name="event", requirements={"id": "\d+"})
     */
    public function eventAction(Request $request, Alias $aliasService, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $title = 'Add new event';
        if ($id > 0)
        {
            $event = $em->getRepository(Event::class)->find($id);
            $title = 'Edit event &bull; ' . $event->getName();
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $event->setUser($this->getUser());

            $em->persist($event);
            $em->flush();

            $event->setAlias($aliasService->makeAlias($event->getName(), $event->getId()));
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Event:event.html.twig', array(
            'title' => $title,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/{alias}", name="event_attend")
     * @Method({"POST"})
     */
    public function eventAttendAction(Alias $aliasService, $alias)
    {
        $id = $aliasService->decodeAliasToID($alias);
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($id);
        $user = $this->getUser();

        $status = $em->getRepository(Participation::class)->attend($event, $user);

        return new JsonResponse(['status' => $status, 'name' => $user->getNick()]);
    }

    /**
     * @Route("/event/{alias}", name="event_details")
     * @Method({"GET"})
     */
    public function eventDetailsAction(Alias $aliasService, $alias)
    {
        $id = $aliasService->decodeAliasToID($alias);
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($id);
        $participation = $em->getRepository(Participation::class)->getParticipation($this->getUser(), $event);
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
        $events = $em->getRepository(Event::class)->getUserEvents($this->getUser());
        return $this->render('AppBundle:Event:myEvetns.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * @Route("/eventsonmap", name="events_on_map")
     */
    public function showOnMapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->getUserEvents($this->getUser());
        return $this->render('AppBundle:Event:eventsOnMap.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * @Route("/nearbyevents", name="nearby_events")
     */
    public function nearbyeventsAction(Request $request, EventService $eventService)
    {
        $events = $eventService->getNearbyEvents($request->request->get('lat', 0), $request->request->get('lng', 0), $request->request->get('radius', 5));
        return new JsonResponse($events);
    }

    /**
     * @Route("/invitations/send/{idEvent}", name="send_invitations", requirements={"idEvent": "\d+"})
     */
    public function sendInvitationAction(Invitation $invitation, $idEvent, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $participants = $em->getRepository(Participation::class)->getParticipatingUsers($idEvent);
        $users = $em->getRepository(User::class)->getUsers($this->getUser(), $participants);
        return new JsonResponse(['status' => $invitation->sendInvitations($idEvent, $users, $mailer)]);
    }
}
