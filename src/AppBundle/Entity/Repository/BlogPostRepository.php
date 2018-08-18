<?php

// /src/AppBundle/Entity/Repository/BlogPostRepository.php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BlogPostRepository extends EntityRepository
{
  public function createFindOneByIdQuery(int $id)
  {
    $query = $this->_em->createQuery(
      "
            SELECT bp, u.username AS username
            FROM AppBundle:BlogPost bp
            INNER JOIN AppBundle:User u WITH bp.uid = u.id
            WHERE bp.id = :id
            "
    );
    $query->setParameter('id', $id);

    return $query;
  }

  public function createFindAllQuery()
  {
    return $this->_em->createQuery(
      "
            SELECT bp, u.username AS author
            FROM AppBundle:BlogPost bp
            INNER JOIN AppBundle:User u WITH bp.uid = u.id
            "
    );
  }
}