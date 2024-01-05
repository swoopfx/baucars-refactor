<?php
namespace Customer\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;

/**
 *
 * @author otaba
 *        
 */
class AdminActiveTripAdpater implements AdapterInterface
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
        return $this->bookingRepository->findAdminActiveTrip($offset, $itemCountPerPage);
        
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count()
    {
        return $this->bookingRepository->findAdminActiveTripCount();
        
    }
    /**
     * @return the $bookingRepository
     */
    public function getBookingRepository()
    {
        return $this->bookingRepository;
    }

    /**
     * @param \Customer\Paginator\CustomerBookingRepository $bookingRepository
     */
    public function setBookingRepository($bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        return $this;
    }



   
}

