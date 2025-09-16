<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private ParameterBagInterface $parameter)
    {
        parent::__construct($registry, Course::class);
    }

    public function addCover(Course $course, UploadedFile $image): void
    {
        $name = uniqid("image_") . '.' . $image->guessExtension();

        $image->move($this->parameter->get('app.cover_image_directory'), $name);

        $course->setImage($name);
    }

       /**
        * @return Course[] Returns an array of Course objects
        */
       public function search($search): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.title LIKE :val')
               ->setParameter('val', '%'.$search.'%')
               ->orderBy('c.title', 'ASC')
               ->setMaxResults(12)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Course
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
