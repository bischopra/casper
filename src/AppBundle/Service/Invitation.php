<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\Event;
use AppBundle\Entity\Participation;

class Invitation
{
    protected $em;
    protected $templating;

    public function __construct(EntityManager $em, EngineInterface $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
    }

    public function sendInvitations($idEvent, $users, \Swift_Mailer $mailer)
    {
        $event = $this->em->getRepository(Event::class)->find($idEvent);

        foreach($users as $user)
        {
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('zbigniew.kowalski@polcode.net')
            ->setTo('zbigniew.kowalski+test@polcode.net')
            ->setBody(
                $this->templating->render(
                    'Emails/invitation.html.twig',
                    array('name' => $event->getName(), 'alias' => $event->getAlias())
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'Emails/invitation.txt.twig',
                    array('name' => $event->getName(), 'alias' => $event->getAlias())
                ),
                'text/plain'
            );

            $this->em->getRepository(Participation::class)->setInvitationAsSent($event, $user);
        }

        return $mailer->send($message);
    }
}
