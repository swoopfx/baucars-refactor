<?php
namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Cars;
use Doctrine\ORM\Query;

/**
 *
 * @author otaba
 *        
 */
class CarRepository extends EntityRepository
{

    // TODO - Insert your code here
    public function count($criteria = null)
    {
        $repo = $this->getEntityManager()->getRepository(Cars::class);
        $result = $repo->createQueryBuilder("b")
            ->select("count(b.id)")
            ->getQuery()
            ->getSingleScalarResult();
        return $result;
    }

    public function findRegisteredCars($offset, $itemsPerPage)
    {
        $repo = $this->getEntityManager()->getRepository(Cars::class);
        $data = $repo->createQueryBuilder("c")
            ->select("c, m, mc, d, u")
            ->leftJoin("c.motorMake", "m")
            ->leftJoin("c.motorClass", "mc")
            ->leftJoin("c.driver", "d")
            ->leftJoin("d.user", "u")
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage)
            ->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }
}

