<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @method Config|null find($id, $lockMode = null, $lockVersion = null)
 * @method Config|null findOneBy(array $criteria, array $orderBy = null)
 * @method Config[]    findAll()
 * @method Config[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function findOneByKey(string $key, bool $enableResultCache = true): ?Config
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.key = :key')
            ->setParameter('key', $key)
            ->getQuery();

        if ($enableResultCache) {
            $query->enableResultCache(null, $this->getCacheKey($key));
        }

        return $query->getOneOrNullResult();
    }

    /**
     * @return Config[]
     */
    public function findByFilter(?string $filter = null): iterable
    {
        $queryBuilder = $this->_em
            ->createQueryBuilder()
            ->select('c')
            ->from($this->getClassName(), 'c', 'c.key');

        if ($filter !== null) {
            $queryBuilder
                ->where('c.key LIKE :pattern')
                ->setParameter('pattern', "%$filter%");
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function getCacheKey(string $key): string
    {
        return u("{$this->getClassName()}.$key")->replace('\\', '_')->folded()->toString();
    }
}
