<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: ['label' => 'Nome'])
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Aceito os termos',
                'label_html' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Você deve aceitar os termos.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, options: [
                'type' => PasswordType::class,
                'first_options' =>
                    [
                        'label' => 'Senha',
                        'help' => 'A senha deve conter pelo menos 6 caracteres, incluindo: uma letra maiúscula, uma letra minúscula e um número.',
                        'attr' => ['autocomplete' => 'new-password'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Insira uma senha',
                            ]),
                            new Length([
                                'max' => 60,
                                'maxMessage' => 'Senha pode conter no máximo {{ limit }} caracteres',
                            ]),
                            new Regex('^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$^', 'Senha inválida')]
                    ],
                'second_options' => ['label' => 'Confirmar Senha'],
                'invalid_message' => 'As senhas não coincidem.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
