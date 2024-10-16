<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @return Tag[]
     */
    public function findAllFiltered(): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('pictures')
            ->where('pictures.deletedAt IS NULL')
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Tag[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function findAllOrdered(): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('tag')
            ->orderBy('tag.createdAt', 'DESC')
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function save(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
