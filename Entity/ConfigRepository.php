<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConfigRepository extends EntityRepository
{
    /**
     * @param string $key
     * @return Config
     */
    public function findOneByKey($key)
    {
        return $this->createQueryBuilder('c')
            ->where('c.key = :key')
            ->setParameter('key', $key)
            ->getQuery()
            ->useResultCache(true, null, $this->getCacheKey($key))
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string|null $filter
     * @return Config[]
     */
    public function findByFilter($filter = null)
    {
        $queryBuilder = $this->_em
            ->createQueryBuilder()
            ->select('c')
            ->from($this->getClassName(), 'c', 'c.key')
        ;

        if ($filter !== null) {
            $queryBuilder
                ->where('c.key LIKE :pattern')
                ->setParameter('pattern', "%{$filter}%")
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $key
     * @return string
     */
    public function getCacheKey($key)
    {
        return sprintf('%s.%s', $this->getClassName(), $key);
    }
}