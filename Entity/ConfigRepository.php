<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;

/**
 * @method \Imatic\Bundle\ConfigBundle\Entity\Config find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method \Imatic\Bundle\ConfigBundle\Entity\Config findOneBy(array $criteria, array $orderBy = null)
 * @method \Imatic\Bundle\ConfigBundle\Entity\Config[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 **/
class ConfigRepository extends EntityRepository
{
    /**
     * @return Config[]
     */
    public function findAll()
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('c')
            ->from($this->getClassName(), 'c', 'c.key')
            ->getQuery()
            ->getResult()
        ;
    }
//    /**
//     * @param string|null $pattern
//     * @return Config[]
//     */
//    public function findByPattern($pattern = null)
//    {
//        $queryBuilder = $this->_em
//            ->createQueryBuilder()
//            ->select('c')
//            ->from($this->getClassName(), 'c', 'c.key')
//        ;
//
//        if ($pattern !== null) {
//            $queryBuilder
//                ->where('c.key LIKE :pattern')
//                ->setParameter('pattern', "%{$pattern}%")
//            ;
//        }
//
//        return $queryBuilder->getQuery()->getResult();
//    }
}