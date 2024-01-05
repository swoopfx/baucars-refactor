<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */
namespace CsnUser\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use CsnUser\Service\UserService;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use CsnUser\Entity\User;

// use Laminas\InputFilter\InputFilter;
// use Laminas\InputFilter\Factory as InputFactory;

/**
 * UserRepository
 *
 * Repository class to extend Doctrine ORM functions with your own
 * using DQL language. More here http://mackstar.com/blog/2010/10/04/using-repositories-doctrine-2
 */
class UserRepository extends EntityRepository
{

    public function findCustomerProfile($id)
    {
        $dql = "SELECT u FROM CsnUser\Entity\User u WHERE u.id = :id";
        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameters([
            "id" => $id
        ])
            ->getResult(Query::HYDRATE_ARRAY);
        return $query;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Doctrine\ORM\EntityRepository::count()
     */
    public function count($criteria = NULL)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select(array(
            'c.id'
        ))
            ->from('CsnUser\Entity\User', 'c')
            ->where("c.role != :role")
            ->setParameter("role", UserService::USER_ROLE_ADMIN)
            ->orderBy("c.id", "DESC");
        
        $result = $query->getQuery()->getResult();
        
        return count($result);
    }

    public function countblackList()
    {}

    public function getItems($offset, $itemCountPerPage)
    {
        $dql = "Select u, s FROM CsnUser\Entity\User u JOIN u.state s where u.role != :role ORDER BY u.id DESC";
        $em = $this->getEntityManager();
        $query = $em->createQuery($dql)
            ->setParameters(array(
            "role" => UserService::USER_ROLE_ADMIN
        ))
            ->setFirstResult($offset)
            ->setMaxResults($itemCountPerPage);
        return $query->getResult(AbstractQuery::HYDRATE_ARRAY);
        
        // $query = $this->getEntityManager()->createQueryBuilder();
        // $query->select(array('u.id', 'u.username', 'u.registrationDate', 'u.isProfiled', "u.userUid", "u.state"))
        // ->from('CsnUser\Entity\User', 'u')
        // ->where("u.role != :role")
        // ->setParameter("role", UserService::USER_ROLE_ADMIN)
        // ->setFirstResult($offset)
        // ->setMaxResults($itemCountPerPage);
        
        // $result = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
        
        // return $result;
    }

    public function getBlackListItems()
    {}

    public function getCustomerCount($criteria = NULL)
    {
        $repo = $this->getEntityManager()->getRepository(User::class);
        $query = $repo->createQueryBuilder("c")
            ->select("count(c.id)")
            ->where("c.role = " . UserService::USER_ROLE_CUSTOMER)
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    public function getCustomerItems($offset, $itemPerPage)
    {
        $repo = $this->getEntityManager()->getRepository(User::class);
        $query = $repo->createQueryBuilder("c")
            ->select([
            "c",
            "st",
            "l"
        ])
            ->where("c.role =" . UserService::USER_ROLE_CUSTOMER)
            ->leftJoin("c.state", "st")
            ->leftJoin("c.lastlogin", "l")
            ->setFirstResult($offset)
            ->setMaxResults($itemPerPage)
            ->orderBy("c.id", "DESC")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        return $query;
    }

    public function getCountNewCustomerThisMonth()
    {
        $dql = "SELECT count(u) FROM CsnUser\Entity\User u WHERE ";
    }
}
