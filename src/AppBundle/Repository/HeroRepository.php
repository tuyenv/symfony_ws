<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Hero;
use Doctrine\ORM\EntityRepository;

class HeroRepository extends EntityRepository
{

    public function createHero($name)
    {
        try {
            $entityHero = new Hero();
            $entityHero->setName($name);

            $this->getEntityManager()->persist($entityHero);
            $this->getEntityManager()->flush();
            return $entityHero;
        } catch (\Exception $e) {
            // write log here
        }
    }

    public function findHeroById($id)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('h')
            ->from('AppBundle:Hero', 'h')
            ->where('h.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function getAll()
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('h')
            ->from('AppBundle:Hero', 'h')
            ->getQuery();

        return $query->getArrayResult();
    }

    public function updateHero($id, $name)
    {
        $heroObject = $this->getEntityManager()->getRepository(Hero::class)->find($id);
        $heroObject->setName($name);
        $this->getEntityManager()->persist($heroObject);
        $this->getEntityManager()->flush();
    }

    public function deleteHero($id)
    {
        $heroObject = $this->getEntityManager()->getRepository(Hero::class)->find($id);
        $this->getEntityManager()->remove($heroObject);
        $this->getEntityManager()->flush();
    }
}
