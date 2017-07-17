<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function getUsers(User $user, $participants)
    {
        $ids[] = $user->getId();
        foreach($participants as $participant)
        {
            $ids[] = $participant->getUser()->getId();
        }
        return $this->getEntityManager()->createQueryBuilder()
                ->select('u')
                ->from('AppBundle:User', 'u')
                ->where('u.id not in ('.implode(',', $ids).')')
                ->getQuery()
                ->getResult();
    }
}
