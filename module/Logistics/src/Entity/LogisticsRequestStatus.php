<?php

namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * initiated
 * Assigned to driver
 * Delivered
 * Canceled
 * Rejected
 * Processing
 *
 * @ORM\Entity
 * @ORM\Table (name="logistics_request_status")
 */
class LogisticsRequestStatus
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;

    /**
     * @ORM\Column (name="status", type="string", nullable=false)
     * @var string
     */
    private $status;
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }



}