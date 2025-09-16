<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Form\CourseType;
use App\Services\CartService;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/course')]
final class CourseController extends AbstractController
{
    #[Route(name: 'app_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository, CartService $cartService, Request $request): Response
    {
        $search = $request->get('search');

        if ($search) {
            $courses = $courseRepository->search($search);
        } else {
            $courses = $courseRepository->findAll();
        }

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'cart' => $cartService->getCourses(),
        ]);
    }

    #[Route('/new', name: 'app_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CourseRepository $repository): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                $repository->addCover($course, $image);
            }

            $course->setTeacher($this->getUser());

            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'Curso adicionado com sucesso');

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_course_show', methods: ['GET'])]
    public function show(Course $course, CartService $cart): Response
    {
        $isInCart = $cart->hasCourse($course, $this->getUser());

        return $this->render('course/show.html.twig', compact('course', 'isInCart'));
    }

    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager, CourseRepository $repository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                $repository->addCover($course, $image);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Curso editado com sucesso');

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($course);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Curso removido com sucesso');

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_TEACHER')]
    #[Route('/sent', name: 'app_sent_courses', methods: ['GET'])]
    public function sentCourses(): Response
    {
        /** @var User */
        $user = $this->getUser();
        $courseList = $user->getCourses();

        return $this->render('course/sent_courses.html.twig', compact('courseList'));
    }
}
