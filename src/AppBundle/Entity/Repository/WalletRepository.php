<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class WalletRepository extends EntityRepository
{
  public function createFindOneByIdQuery(int $id)
  {
    $query = $this->_em->createQuery(
      "SELECT wl
            FROM AppBundle:Wallet wl
            WHERE wl.userId = :id
            "
    );

    $query->setParameter('id', $id);

    return $query;
  }

}