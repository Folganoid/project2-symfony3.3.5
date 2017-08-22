<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommentsRepository extends EntityRepository
{
    public function findAllComments($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c.id, c.content, c.date, c.rate, u.username FROM AppBundle:Comments c, AppBundle:User u WHERE c.markerId ='.$id.' AND c.userId = u.id ORDER BY c.date DESC'
            )
            ->getArrayResult();
    }
}
