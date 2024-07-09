<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PullRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PullRequest>
 *
 * @method PullRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PullRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PullRequest[]    findAll()
 * @method PullRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PullRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PullRequest::class);
    }

    /**
     * @return PullRequest[]
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

    public function findPullRequestYears(): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare('
            SELECT "created_year" AS "year", COUNT(*) AS "count"
                FROM "pull_request"
                WHERE "deleted_at" IS NULL
                GROUP BY "created_year"
                ORDER BY "created_year" DESC
'       );

        return $statement
            ->executeQuery()
            ->fetchAllAssociative()
            ;
    }

    /**
     * @param int $year
     * @return PullRequest[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByYear(int $year): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('pullRequest')
            ->where('pullRequest.createdYear = :year')
            ->andWhere('pullRequest.deletedAt IS NULL')
            ->orderBy('pullRequest.createdAt', 'DESC')
            ->setParameter('year', $year)
            ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     * @return PullRequest[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function findLast(int $limit): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('pullRequest')
            ->where('pullRequest.deletedAt IS NULL')
            ->orderBy('pullRequest.weight', 'DESC')
            ->orderBy('pullRequest.createdAt', 'DESC')
            ->setMaxResults($limit)
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function save(PullRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PullRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
