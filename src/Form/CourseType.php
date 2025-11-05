<?php

namespace App\Form;

use App\Entity\Course;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class CourseType extends AbstractType
{
    public function __construct(private CategoryRepository $category) 
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: ['label' => 'Título'])
            ->add('description', TextareaType::class, options: ['label' => 'Descrição', 'attr' => ['maxlength' => 255]])
            ->add('category', ChoiceType::class, [
                'choices' => $this->category->findInOrder(),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Categorias',
                'mapped' => false,
                'attr' => [
                    'class' => 'd-none'
                ],
                'constraints' => [
                    new Count(min: 1, minMessage: "Selecione pelo menos 1 categoria", max: 5, maxMessage: "Selecione no máximo {{ limit }} categorias"),
                ]
            ])
            ->add('image', FileType::class, ['mapped' => false, 'required' => false, 'label' => 'Imagem', 'constraints' => [new Image()]])
            ->add('price', MoneyType::class, ['currency' => 'BRL', 'label' => 'Preço'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
