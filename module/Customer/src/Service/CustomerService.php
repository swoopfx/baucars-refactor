<?php
namespace Customer\Service;

use Customer\Entity\CustomerBooking;
use CsnUser\Entity\User;
use Doctrine\ORM\EntityManager;
use General\Entity\BookingClass;
use Laminas\Session\Container;
use Laminas\Authentication\AuthenticationService;
use General\Entity\BookingStatus;
use General\Entity\BookingType;
use General\Service\GeneralService;
use General\Entity\BillingMethod;

/**
 *
 * @author otaba
 *        
 */
class CustomerService
{

    /**
     *
     * @var AuthenticationService
     */
    private $auth;

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

    const BILLING_METHOD_HOURLY = 10;

    const BILLING_METHOD_DAILY = 100;

    const BOOKING_CLASS_REGULAR = 10;

    const BOOKING_CLASS_EXECUTIVE = 100;

    const BOOKING_SUBSCRIPTION = 20;

    const BOOKING_INSTANT = 50;

    const BOOKING_STATUS_INITIATED = 5;

    const BOOKING_STATUS_ACTIVE = 10;

//     const BOOKING_STATUS_IN_TRANSIT = 600;

    const BOOKING_STATUS_CANCELED = 100;

    const BOOKING_STATUS_PROCESSING = 500;

    const BOOKING_STATUS_ASSIGN = 501;

    const BOOKING_STATUS_PAID = 20;

    const BOOKING_STATUS_COMPLETED = 30;

    const BOOKING_STATUS_UNPAID = 200;

    const BOOKING_STATUS_PENDING = 300;

    /**
     *
     * @var \DateTime
     */
    private $bookingStartDate;

    /**
     *
     * @var \DateTime
     */
    private $bookingEndData;

    /**
     *
     * @var string
     */
    private $billingMethod;

    /**
     *
     * @var string
     */
    private $bookingClass;

    /**
     *
     * @var string
     */
    private $bookingService;

    /**
     *
     * @var string
     */
    private $bookingPickupAddress;

    /**
     *
     * @var Container
     */
    private $bookingSession;

    private $bookingType;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    private function calculateTimeInHours()
    {
        $diff = $this->bookingEndData->diff($this->bookingStartDate);
        $hours = $diff->h;
        return $hours + ($diff->days * 24);
    }

    private function calculateTimeInDays()
    {
        $diff = $this->bookingEndData->diff($this->bookingStartDate);
        return $diff->days;
    }

    private function getClassPrice($classId)
    {
        $em = $this->entityManager;
        $entity = $em->find(BookingClass::class, $classId);
        return $entity;
    }

    public function calculatePrice()
    {
        $billingClass = $this->getClassPrice($this->bookingClass);
        if ($this->billingMethod == self::BILLING_METHOD_HOURLY) {
            $totalHours = $this->calculateTimeInHours();
            $totalHours = ($totalHours == 0 ? 1 : $totalHours);
            $totalPrice = $totalHours * $billingClass->getPricingPerHour();
            return $totalPrice;
        } else {
            $totalDays = $this->calculateTimeInDays();
            $totalDays = ($totalDays == 0 ? 1 : $totalDays);
            $totalPrice = $totalDays * $billingClass->getPricingPerDay();
            return $totalPrice;
        }
    }

    public function createBooking()
    {
        $booking = new CustomerBooking();
        $auth = $this->auth;
        $em = $this->entityManager;
        
        $booking->setCreatedOn(new \DateTime())
            ->setEndTime($this->bookingEndData)
            ->setBookingUid(self::bookingUid())
            ->setBillingMethod($em->find(BillingMethod::class, $this->billingMethod))
            ->setStartTime($this->bookingStartDate)
            ->setPickupAddress($this->bookingPickupAddress)
            ->setUser($this->auth->getIdentity())
            ->setBookingClass($em->find(BookingClass::class, $this->bookingClass))
            ->setStatus($em->find(BookingStatus::class, self::BOOKING_STATUS_INITIATED))
            ->setBookingType($em->find(BookingType::class, $this->bookingType));
        
        // $generalService = $this->generalService;
        // $pointer["to"] = $auth->getIdentity()->getEmail();
        // $pointer["fromName"] = "Bau Cars Limited";
        // $pointer['subject'] = "Booking Initiated";
        
        // $template['template'] = "";
        // $template["var"] = [
        
        // ];
        
        // $generalService->sendMails($pointer, $template);
        return $booking;
        
        // Send Booking mail
    }
    
    
    

    public static function bookingUid()
    {
        return uniqid("book");
    }

    public function getBookingHistory()
    {
        if ($this->auth == null) {
            throw new \Exception("Not authenticated user");
        }
        //
        $em = $this->entityManager;
        $history = $em->getRepository(CustomerBooking::class)->findBookingHistory($this->auth->getIdentity()
            ->getId());
        return $history;
    }

    public function getProfile()
    {
        if ($this->auth == null)
            throw new \Exception("Not Authenticated");
        $em = $this->entityManager;
        $profile = $em->getRepository(User::class)->findCustomerProfile($this->auth->getIdentity()
            ->getId());
        return $profile;
    }

    public function getAllBookingServiceType()
    {
        return $this->entityManager->getRepository(CustomerBooking::class)->getAllBookingType();
    }

    public function getAllBookingClass()
    {
        return $this->entityManager->getRepository(CustomerBooking::class)->findAllBookingClass();
    }

    public function getAllInitiatedBooking()
    {
        return $this->entityManager->getRepository(CustomerBooking::class)->findAllInititedBooking($this->auth->getIdentity()
            ->getId());
    }

    public function getAllBillingMethod()
    {
        return $this->entityManager->getRepository(CustomerBooking::class)->findBillingMethod();
    }

    public function getAllCustomerCount()
    {
        $em = $this->entityManager;
        $repo = $em->getRepository(User::class);
        $result = $repo->createQueryBuilder('a')
            ->where('a.role=30')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result;
    }

    // public function
    /**
     *
     * @return the $auth
     */
    public function getAuth()
    {
        return $this->auth;
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
     * @param field_type $auth            
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     *
     * @param field_type $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @param field_type $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return the $bookingStartDate
     */
    public function getBookingStartDate()
    {
        return $this->bookingStartDate;
    }

    /**
     *
     * @return the $bookingEndData
     */
    public function getBookingEndData()
    {
        return $this->bookingEndData;
    }

    /**
     *
     * @return the $billingMethod
     */
    public function getBillingMethod()
    {
        return $this->billingMethod;
    }

    /**
     *
     * @return the $bookingClass
     */
    public function getBookingClass()
    {
        return $this->bookingClass;
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
     * @param DateTime $bookingStartDate            
     */
    public function setBookingStartDate($bookingStartDate)
    {
        $this->bookingStartDate = $bookingStartDate;
        return $this;
    }

    /**
     *
     * @param DateTime $bookingEndData            
     */
    public function setBookingEndData($bookingEndData)
    {
        $this->bookingEndData = $bookingEndData;
        return $this;
    }

    /**
     *
     * @param string $billingMethod            
     */
    public function setBillingMethod($billingMethod)
    {
        $this->billingMethod = $billingMethod;
        return $this;
    }

    /**
     *
     * @param string $bookingClass            
     */
    public function setBookingClass($bookingClass)
    {
        $this->bookingClass = $bookingClass;
        return $this;
    }

    /**
     *
     * @param string $bookingService            
     */
    public function setBookingService($bookingService)
    {
        $this->bookingService = $bookingService;
        return $this;
    }

    /**
     *
     * @return the $bookingSession
     */
    public function getBookingSession()
    {
        return $this->bookingSession;
    }

    /**
     *
     * @param \Laminas\Session\Container $bookingSession            
     */
    public function setBookingSession($bookingSession)
    {
        $this->bookingSession = $bookingSession;
        return $this;
    }

    /**
     *
     * @return the $bookingType
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }

    /**
     *
     * @param field_type $bookingType            
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;
        return $this;
    }
    /**
     * @return the $bookingPickupAddress
     */
    public function getBookingPickupAddress()
    {
        return $this->bookingPickupAddress;
    }

    /**
     * @param string $bookingPickupAddress
     */
    public function setBookingPickupAddress($bookingPickupAddress)
    {
        $this->bookingPickupAddress = $bookingPickupAddress;
        return $this;
    }

}

