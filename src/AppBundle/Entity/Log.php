<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=60, nullable=true)
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=10, nullable=false)
     */
    private $method;

    /**
     * @var string
     *
     * @ORM\Column(name="end_point", type="string", length=255, nullable=false)
     */
    private $endPoint;

    /**
     * @var string
     *
     * @ORM\Column(name="query_string", type="string", length=255, nullable=true)
     */
    private $queryString;

    /**
     * @var int
     *
     * @ORM\Column(name="response_status", type="smallint", nullable=false)
     */
    private $responseStatus;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="id")
     * })
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=32, nullable=true)
     */
    private $apiKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set service
     *
     * @param string $service
     * @return Log
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set method
     *
     * @param string $method
     * @return Log
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set endPoint
     *
     * @param string $endPoint
     * @return Log
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    /**
     * Get endPoint
     *
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * Set queryString
     *
     * @param string $queryString
     * @return Log
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * Get queryString
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Set responseStatus
     *
     * @param integer $responseStatus
     * @return Log
     */
    public function setResponseStatus($responseStatus)
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    /**
     * Get responseStatus
     *
     * @return integer
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }

    /**
     * Set uid
     *
     * @param \AppBundle\Entity\User $uid
     * @return Log
     */
    public function setUid(\AppBundle\Entity\User $uid = null)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return \AppBundle\Entity\User
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Log
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Log
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }
}
