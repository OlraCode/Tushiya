<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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
    public function search(?string $search, ?string $category, ?string $order): array
    {
        $query = $this->createQueryBuilder('c')->where('c.isVerified = 1');

        if (!empty($search)) {
            $query
                ->andWhere('c.title LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }
        if (!empty($category)) {
            $query
                ->join('c.categories', 'cat')
                ->andWhere('cat.slug = :category')
                ->setParameter('category', $category);
        }
        if (!empty($order)) {
            $orderList = ['title', 'id', 'price'];
            if (in_array($order, $orderList)) {
                $query->orderBy("c.{$order}", 'ASC');
            }
        }

        return $query->getQuery()->getResult();
    }

    public function findNotVerified(): array
    {
        return $this->findBy(['isVerified' => 0, 'refuseMessage' => null]);
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
