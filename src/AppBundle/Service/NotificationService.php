<?php

namespace AppBundle\Service;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Notification;

class NotificationService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function sendNotificationsOnEventAdd(Event $event, \Swift_Mailer $mailer)
    {
        if ($event->getIsPrivate())
        {
            return false;
        }
        foreach($this->em->getRepository(Notification::class)->getNotificationsAroundEvent((float) $event->getLatitude(), (float) $event->getLongitude()) as $notify)
        {
            $mailer->send($this->prepareNewEventMessage($event));
        }
    }

    public function prepareNewEventMessage(Event $event)
    {
        return (new \Swift_Message('New Event ' . $event->getName()))
        ->setFrom('zbigniew.kowalski@polcode.net')
        ->setTo('zbigniew.kowalski+test@polcode.net')
        ->setBody(
            $this->templating->render(
                'Emails/newEvent.html.twig',
                array('name' => $event->getName(), 'alias' => $event->getAlias())
            ),
            'text/html'
        )
        ->addPart(
            $this->templating->render(
                'Emails/newEvent.txt.twig',
                array('name' => $event->getName(), 'alias' => $event->getAlias())
            ),
            'text/plain'
        );
    }
}
