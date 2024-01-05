<?php
namespace Driver\Service;

use General\Service\GeneralService;
use Doctrine\ORM\EntityManager;
use Driver\Entity\DriverBio;
use Doctrine\ORM\Query;
use Customer\Service\CustomerService;
use Customer\Entity\Bookings;
use Customer\Service\BookingService;
use Laminas\Session\Container;

/**
 *
 * @author otaba
 *        
 */
class DriverService
{

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var BookingService
     */
    private $bookingService;

    /**
     *
     * @var Container
     */
    private $amotizedSession;

    const DRIVER_STATUS_FREE = 10;

    const DRIVER_STATUS_ENGAGED = 50;

    const DRIVER_STATUS_ASSIGNED = 100;

    // TODO - Insert your code here
    
    /**
     * These are drivers that are available for service
     */
    public function findAllInactiveDrivers()
    {
        $em = $this->entityManager;
        
        $repo = $em->getRepository(DriverBio::class);
        $result = $repo->createQueryBuilder("d")
            ->addSelect("b")
            ->addSelect("u")
            ->leftJoin("d.user", "u")
            ->leftJoin("d.booking", "b")
            ->leftJoin("b.status", "s")
            ->where("d.driverState = :state")
            ->andWhere("d.isActive = :active")
            ->setParameters([
            "state" => self::DRIVER_STATUS_FREE,
            "active" => TRUE
        ])
            ->getQuery();
        
        $res = $result->getResult(Query::HYDRATE_ARRAY);
        return $res;
    }

    /**
     *
     * @param
     *            $calss
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllInactiveClassDriver($calss)
    {
        $em = $this->entityManager;
        $repo = $em->getRepository(DriverBio::class);
        $dql = "SELECT d FROM Driver\Entity\DriverBio d LEFT JOIN d.user u LEFT JOIN d.assisnedCar ac  WHERE d.activeSession IS NULL AND  ac.motorClass = :class ORDER BY d.id DESC";
        $result = $em->createQuery($dql)
            ->setParameters([
            "sess" => NULL,
            "class" => $class
        ])
            ->getResult(Query::HYDRATE_ARRAY);
        return $result;
    }

    /**
     *
     * @param Bookings $booking            
     */
    public function amotizedTrip($booking)
    {
        $generalService = $this->generalService;
        $bookingService = $this->bookingService;
        $amotizedSession = $this->amotizedSession;
        $bookingSession = $bookingService->getBookingSession();
        $bookingSession->isReturnTrip = $booking->getIsReturnTrip();
        $bookingSession->objectpickupDate = $booking->getPickupDate();
        // $bookingSession->objectreturnDate = $booking->getReturnDate();
        $appSettings = $generalService->getAppSeettings();
        $em = $this->entityManager;
        $appSettings = $generalService->getAppSeettings();
        
        $estimatedSeconds = $booking->getCalculatedTimeValue();
        $estimateMinutes = floor($estimatedSeconds / 60);
        
        $estimatedDistance = $booking->getCalculatedDistanceValue();
        $activeTrip = $booking->getTrip();
        $actualStarttime = $booking->getTrip()->getStarted();
        $actualEndtime = $booking->getTrip()->getEnded();
        
        $actualTimeDifference = $actualStarttime->diff($actualEndtime);
        $actualMinutes = $actualTimeDifference->days * 24 * 60;
        $actualMinutes += $actualTimeDifference->h * 60;
        $actualMinutes += $actualTimeDifference->i;
        // var_dump($actualMinutes);
        $usableMinutes = $estimateMinutes + $appSettings->getGracePeriod();
        // var_dump($usableMinutes);
        $price = 0;
        // if ($usableMinutes >= $actualMinutes) {
        // // call
        // var_dump("ggggg");
        // $price = $bookingService->setDmDistance($estimatedDistance)->priceCalculator();
        // } else {
        $price = $bookingService->setDmDistance($estimatedDistance)->priceCalculator();
        $extraTimeUsed = $actualMinutes - $usableMinutes;
        
        $amotizedSession->extraTimeUsed = $extraTimeUsed;
        // var_dump($extraTimeUsed);
        if ($extraTimeUsed < 0) {
            $amotizedSession->extraTimeUsed = 0;
        }
        if ($extraTimeUsed > 60) {
            $extraTimeMultiplier = $extraTimeUsed / 30;
            $extraCost = (ceil($extraTimeMultiplier) * $appSettings->getExtraHourFee());
            $amotizedSession->extraCost = $extraCost;
            $price = $price + $extraCost;
        }
        // }
        
        $amotizedSession->price = $price;
        $amotizedSession->estimateMinutes = $estimateMinutes;
        $amotizedSession->estimatedDistance = $estimatedDistance;
        
        $amotizedSession->actualMinutes = $actualMinutes;
    }

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public static function driverUid()
    {
        return uniqid();
    }

    /**
     *
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return the $bookingService
     */
    public function getBookingService()
    {
        return $this->bookingService;
    }

    /**
     *
     * @param \Customer\Service\BookingService $bookingService            
     */
    public function setBookingService($bookingService)
    {
        $this->bookingService = $bookingService;
        return $this;
    }

    /**
     *
     * @return the $amotizedSession
     */
    public function getAmotizedSession()
    {
        return $this->amotizedSession;
    }

    /**
     *
     * @param \Laminas\Session\Container $amotizedSession            
     */
    public function setAmotizedSession($amotizedSession)
    {
        $this->amotizedSession = $amotizedSession;
        return $this;
    }
}

