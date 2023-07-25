<?php



namespace App\Repository;

use App\Entity\Picture;
use App\Entity\PullRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Picture>
 *
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * @param int $limit
     * @return Picture[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function findLast(int $limit): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('picture')
            ->orderBy('picture.drawnAt', 'DESC')
            ->setMaxResults($limit)
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function findPicturesYears(): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $statement = $connection->prepare('
            SELECT "drawn_year" AS "year"
                FROM "picture"
                GROUP BY "drawn_year"
                ORDER BY "drawn_year" DESC
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
            ->createQueryBuilder('pictures')
            ->where('pictures.drawnYear = :year')
            ->orderBy('pictures.drawnAt', 'DESC')
            ->setParameter('year', $year)
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function save(Picture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Picture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Picture[] Returns an array of Picture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Picture
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
