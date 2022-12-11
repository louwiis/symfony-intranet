<?php

namespace App\Repository;

use App\Entity\StudentGrade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentGrade>
 *
 * @method StudentGrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentGrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentGrade[]    findAll()
 * @method StudentGrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentGradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentGrade::class);
    }

    public function save(StudentGrade $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StudentGrade $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByGradeId(int $gradeId): array
    {
        return $this->createQueryBuilder('sg')
            ->andWhere('sg.grade = :gradeId')
            ->setParameter('gradeId', $gradeId)
            ->getQuery()
            ->getResult();
    }

    public function findAllByStudentId(int $studentId): array
    {
        return $this->createQueryBuilder('sg')
            ->andWhere('sg.student = :studentId')
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return StudentGrade[] Returns an array of StudentGrade objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StudentGrade
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
