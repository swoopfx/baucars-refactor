<?php
namespace Application\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;
use Application\Entity\Repository\CarRepository;

/**
 *
 * @author otaba
 *        
 */
class CarAdapater implements AdapterInterface
{
    /**
     * 
     * @var CarRepository
     */
    private $carRepository;

    // TODO - Insert your code here
    
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
        return $this->carRepository->findRegisteredCars($offset, $itemCountPerPage);
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count()
    {
        return $this->carRepository->count(); 
    }
    /**
     * @return the $carRepository
     */
    public function getCarRepository()
    {
        return $this->carRepository;
    }

    /**
     * @param \Application\Entity\Repository\CarRepository $carRepository
     */
    public function setCarRepository($carRepository)
    {
        $this->carRepository = $carRepository;
        return $this;
    }


}

