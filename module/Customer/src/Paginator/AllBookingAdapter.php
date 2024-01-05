<?php
namespace Customer\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;
use Customer\Entity\Repostory\CustomerBookingRepository;

/**
 *
 * @author otaba
 *        
 */
class AllBookingAdapter implements AdapterInterface
{

    /**
     *
     * @var CustomerBookingRepository
     */
    private $bookingRepository;

    public function getItems($offset, $itemCountPerPage)
    {
        
        return $this->bookingRepository->findBookingItems($offset, $itemCountPerPage);
    }

    public function count()
    {
        return $this->bookingRepository->countBooking();
    }

    /**
     *
     * @return the $bookingRepository
     */
    public function getBookingRepository()
    {
        return $this->bookingRepository;
    }

    /**
     *
     * @param field_type $bookingRepository            
     */
    public function setBookingRepository($bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        return $this;
    }
}

