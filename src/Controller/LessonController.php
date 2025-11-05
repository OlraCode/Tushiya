<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Repository\CourseRepository;
use Closure;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LessonController extends AbstractController
{
    #[Route('/course/{id}/lesson/{number}', name: 'app_lesson')]
    public function index(Course $course, int $number): Response
    {
        $lessons = $course->getLessons();
        $activeLesson = $lessons->filter(fn (Lesson $lesson) => $lesson->getNumber() == $number)->first();

        return $this->render('lesson/index.html.twig', compact('lessons', 'activeLesson'));
    }
}
