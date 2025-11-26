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
            ['admin@example.com', 'Admin1234', ['ROLE_ADMIN'], '10012553050'],
            ['teacher@example.com', 'Teacher1234', ['ROLE_TEACHER'], '97817467005'],
            ['student@example.com', 'student1234', ['ROLE_USER'], '40895329069'],
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
            $newUser->setCpf($user[3]);

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
        
        $manager->flush();
    }
}
