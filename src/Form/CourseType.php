<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options: ['label' => 'Título'])
            ->add('description', options: ['label' => 'Descrição'])
            ->add('category', options: ['label' => 'Categoria'])
            ->add('image', FileType::class, ['mapped' => false, 'required' => false, 'label' => 'Imagem'])
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
