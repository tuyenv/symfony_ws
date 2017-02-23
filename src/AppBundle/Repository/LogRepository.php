<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Log;
use Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository
{
    public function writeLog(
        $service,
        $method,
        $endPoint,
        $queryString,
        $responseStatus,
        $apiKey,
        $uid = 0
    ) {
        try {
            $entityLog = new Log();
            $entityLog->setService($service);
            $entityLog->setMethod($method);
            $entityLog->setEndPoint($endPoint);
            $entityLog->setQueryString($queryString);
            $entityLog->setResponseStatus($responseStatus);
            $entityLog->setApiKey($apiKey);
            $entityLog->setCreatedDate(new \DateTime());

            if ($uid) {
                $entityUser = $this->getEntityManager()->getRepository('AppBundle:User')->find($uid);
                if (!$entityUser) {
                    throw new \Exception('Something happened with uid');
                }
                $entityLog->setUid($entityUser);
            }

            $this->getEntityManager()->persist($entityLog);
            $this->getEntityManager()->flush();
            return $entityLog;
        } catch (\Exception $e) {
            // write log here
        }
    }
}
