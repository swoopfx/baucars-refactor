<?php
namespace Driver\Entity\Factory;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 *
 * @author otaba
 *        
 */
class DriverBioRepository extends EntityRepository
{

    public function count($criteria = NULL)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder("a")
            ->select("count(a.id)")
            ->from("Driver\Entity\DriverBio", "a")
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder("d")
            ->select([
            "d",
            "c",
            "ds",
            "mm",
            "u"
        ])
            ->from("Driver\Entity\DriverBio", "d")
            ->leftJoin("d.assisnedCar", "c")
            ->leftJoin("c.motorMake", "mm")
            ->leftJoin("d.user", "u")
            ->leftJoin("d.driverState", "ds")
            ->where("d.isActive = :active")
            ->setParameters([
                "active"=>TRUE
            ])
            ->setMaxResults($itemCountPerPage)
            ->setFirstResult($offset)
            ->orderBy("d.id", "DESC")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        return $query;
    }
}

