<?php
namespace CsnUser\Service;

/**
 *
 * @author swoopfx
 *        
 */
class NewUserService
{

    protected $entityManager;

    public function __construct()
    {}

    public function selectUserDQL($usernameOrEmail)
    {
        $dql = "SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'";
        $query = $this->entityManager->createQuery($dql)->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
        return $query;
    }

    public function setEntityManager($em)
    {
        $this->entityManager = $em;
        return $this;
    }
}

