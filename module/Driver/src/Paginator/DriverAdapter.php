<?php
namespace Driver\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;
use Driver\Entity\Factory\DriverBioRepository;


/**
 *
 * @author otaba
 *        
 */
class DriverAdapter implements AdapterInterface
{
    
    /**
     * 
     * @var DriverBioRepository
     */
    private $driverRepository;
    
    /**
     * {@inheritDoc}
     * @see \Laminas\Paginator\Adapter\AdapterInterface::getItems()
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->driverRepository->getItems($offset, $itemCountPerPage);
        
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count()
    {
        return $this->driverRepository->count();
        
    }
    /**
     * @return the $driverRepository
     */
    public function getDriverRepository()
    {
        return $this->driverRepository;
    }

    /**
     * @param \Driver\Entity\Factory\DriverBioRepository $driverRepository
     */
    public function setDriverRepository($driverRepository)
    {
        $this->driverRepository = $driverRepository;
        return $this;
    }

   



   
}

