<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $password, private UserRepository $userRepository) 
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['admin@example.com', 'Admin1234', ['ROLE_ADMIN']],
            ['teacher@example.com', 'Teacher1234', ['ROLE_TEACHER']],
            ['student@example.com', 'student1234', ['ROLE_USER']],
        ];

        foreach ($users as $user) {

            $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $user[0]]);
            if ($existingUser) {
                continue;
            }

            $newUser = new User;
            $newUser->setName('Test User');
            $newUser->setIsVerified(true);
            $newUser->setEmail($user[0]);
            $newUser->setPassword($this->password->hashPassword($newUser, $user[1]));
            $newUser->setRoles($user[2]);

            $manager->persist($newUser);
        }

        $categories = [
            ['name' => 'Programação', 'slug' => 'programacao'],
            ['name' => 'Design e UX', 'slug' => 'design-ux'],
            ['name' => 'Marketing Digital', 'slug' => 'marketing-digital'],
            ['name' => 'Idiomas', 'slug' => 'idiomas'],
            ['name' => 'Música', 'slug' => 'musica'],
            ['name' => 'Fotografia e Vídeo', 'slug' => 'fotografia-video'],
            ['name' => 'Negócios e Empreendedorismo', 'slug' => 'negocios-empreendedorismo'],
            ['name' => 'Saúde e Bem-Estar', 'slug' => 'saude-bem-estar'],
            ['name' => 'Culinária e Gastronomia', 'slug' => 'culinaria-gastronomia'],
            ['name' => 'Ciência e Tecnologia', 'slug' => 'ciencia-tecnologia'],
        ];

        foreach ($categories as $category) {
            $newCategory = new Category;
            $newCategory->setName($category['name']);
            $newCategory->setSlug($category['slug']);

            $manager->persist($newCategory);
        }

        $course = new Course;
        $course->setTitle('Curso Teste');
        $course->setDescription('Testando o meu mais novo curso com as aulas inclusas.');
        $course->setPrice('50');
        $course->setTeacher($this->userRepository->findOneBy(['email' => 'teacher@example.com']));
        $course->setIsVerified(true);
        $manager->persist($course);

        $lesson1 = new Lesson;
        $lesson1->setCourse($course);
        $lesson1->setNumber(1);
        $lesson1->setTitle('Aula 1');
        $lesson1->setVideo('https://www.youtube.com/embed/S9uPNppGsGo?si=vewCqnUyZlAeqKth'); 
        $manager->persist($lesson1);

        $lesson2 = new Lesson;
        $lesson2->setCourse($course);
        $lesson2->setNumber(2);
        $lesson2->setTitle('Aula 2');
        $lesson2->setVideo('https://www.youtube.com/embed/S9uPNppGsGo?si=vewCqnUyZlAeqKth');
        $manager->persist($lesson2);

        $lesson3 = new Lesson;
        $lesson3->setCourse($course);
        $lesson3->setNumber(3);
        $lesson3->setTitle('Aula 3');
        $lesson3->setVideo('https://www.youtube.com/embed/S9uPNppGsGo?si=vewCqnUyZlAeqKth');
        $manager->persist($lesson3);

        $manager->flush();
    }
}
