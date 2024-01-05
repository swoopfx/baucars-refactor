<?php
namespace Customer\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;
use Customer\Entity\Repostory\CustomerBookingRepository;

/**
 *
 * @author otaba
 *        
 */
class AdminCanceledBookingAdapter implements AdapterInterface
{
    
    /**
     *
     * @var CustomerBookingRepository
     */
    private $bookingRepository;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    /**
     * {@inheritDoc}
     * @see \Laminas\Paginator\Adapter\AdapterInterface::getItems()
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->bookingRepository->findAdminCanceledBooking($offset, $itemCountPerPage);
        
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count()
    {
        return $this->bookingRepository->findAdminCancelBookingCount();
        
    }
    /**
     * @return the $bookingRepository
     */
    public function getBookingRepository()
    {
        return $this->bookingRepository;
    }

    /**
     * @param \Customer\Entity\Repostory\CustomerBookingRepository $bookingRepository
     */
    public function setBookingRepository($bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        return $this;
    }



   
}

