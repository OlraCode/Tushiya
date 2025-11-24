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
use Symfony\Component\Validator\Constraints\Count;

final class LessonController extends AbstractController
{
    #[Route('/course/{id}/lesson/{number}/show', name: 'app_lesson')]
    public function index(Course $course, int $number): Response
    {
        $lessons = $course->getLessons();
        $activeLesson = $course->getLesson($number);

        if ($lessons->isEmpty()) {
            return $this->redirectToRoute('app_lesson_new', ['id' => $course->getId()]);
        }

        return $this->render('lesson/index.html.twig', compact('lessons', 'activeLesson'));
    }

    #[Route('/course/{id}/lesson/new', name: 'app_lesson_new')]
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

        return $this->render('lesson/_form.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    #[Route('/course/{id}/lesson/{number}/edit', name: 'app_lesson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, int $number, EntityManagerInterface $entityManager): Response
    {
        $lesson = $course->getLesson($number);
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Aula editada com sucesso.');

            return $this->redirectToRoute('app_lesson', ['id' => $course->getId(), 'number' => $number]);
        }
        return $this->render('lesson/_form.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);

    }

    #[Route('/course/{id}/lesson/{number}/remove', name: 'app_lesson_remove', methods: ['POST'])]
    public function remove(Course $course, int $number, EntityManagerInterface $entityManager): Response
    {
        $lesson = $course->getLesson($number);
        $entityManager->remove($lesson);
        $entityManager->flush();

        $this->addFlash('success', 'Aula removida com sucesso.');

        return $this->redirectToRoute('app_lesson', ['id' => $course->getId(), 'number' => 1]);
    }
}
