<?php
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConfigRepository extends EntityRepository
{
    /**
     * @param string $key
     * @param bool   $useResultCache
     * @return Config
     */
    public function findOneByKey($key, $useResultCache = true)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.key = :key')
            ->setParameter('key', $key)
            ->getQuery();

        if ($useResultCache) {
            $query->useResultCache(true, null, $this->getCacheKey($key));
        }

        return $query->getOneOrNullResult();
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
            ->from($this->getClassName(), 'c', 'c.key');

        if ($filter !== null) {
            $queryBuilder
                ->where('c.key LIKE :pattern')
                ->setParameter('pattern', "%{$filter}%");
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