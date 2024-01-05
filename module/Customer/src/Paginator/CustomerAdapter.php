<?php
namespace Customer\Paginator;

use Laminas\Paginator\Adapter\AdapterInterface;
use CsnUser\Entity\Repository\UserRepository;

/**
 *
 * @author otaba
 *        
 */
class CustomerAdapter implements AdapterInterface
{

    /**
     * 
     * @var UserRepository
     */
    private $customerRepository;

    // private
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\Paginator\Adapter\AdapterInterface::getItems()
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->customerRepository->getCustomerItems($offset, $itemCountPerPage);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        return $this->customerRepository->getCustomerCount();
    }
    /**
     * @return the $customerRepository
     */
    public function getCustomerRepository()
    {
        return $this->customerRepository;
    }

    /**
     * @param field_type $customerRepository
     */
    public function setCustomerRepository($customerRepository)
    {
        $this->customerRepository = $customerRepository;
        return $this;
    }

}

