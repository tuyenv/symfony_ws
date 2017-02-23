<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function findUserByApiKey($apiKey)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u.id')
            ->from('AppBundle:User', 'u')
            ->where('u.apiKey = :apiKey')
            ->setParameter('apiKey', $apiKey)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function findUserByUid($uid)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.id = :uid')
            ->setParameter('uid', $uid)
            ->getQuery();

        return $query->getArrayResult();
    }

    public function getJsonData($arrData, $fullInfo = false)
    {
        $jsonData = array(
            'uid' => $arrData['id'],
            'name' => $arrData['name'],
            'age' => $arrData['age'],
        );

        if ($fullInfo) {
            $jsonData['key'] = $arrData['apiKey'];
            $jsonData['created'] = $arrData['createdDate']->format('Y-m-d H:i:s');
        }

        return $jsonData;
    }
}
