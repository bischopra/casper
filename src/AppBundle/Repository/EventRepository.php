<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getPublicEvents()
    {
        return $this->findBy(['isPrivate' => 0], ['eventDate' => 'desc']);
    }
}
