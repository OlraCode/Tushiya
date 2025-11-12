<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Form\LessonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LessonController extends AbstractController
{
    #[Route('/course/{id}/lesson/{number}/show', name: 'app_lesson')]
    public function index(Course $course, int $number): Response
    {
        $lessons = $course->getLessons();
        $activeLesson = $lessons->filter(fn (Lesson $lesson) => $lesson->getNumber() == $number)->first();

        return $this->render('lesson/index.html.twig', compact('lessons', 'activeLesson'));
    }

    #[Route('/course/{id}/lesson/new')]
    public function new(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $lesson = new Lesson;
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setCourse($course);

            $lessonQty = $course->getLessons()->count();
            $lesson->setNumber($lessonQty + 1);

            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('app_lesson', [
                'id' => $course->getId(),
                'number' => $lesson->getNumber()
            ]);
        }

        return $this->render('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }
}
