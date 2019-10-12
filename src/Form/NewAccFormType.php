<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NewAccFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
                'required' => true,
                'error_mapping' => true,
                'attr' => [
                    'placeholder' => 'Podaj login'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła nie są identyczne',
                'options' => [
                    'attr' => [
                        'class' => 'password-field',
                    ]
                ],
                'required' => true,
                'first_options'  => ['label' => 'Podaj hasło'],
                'second_options' => [
                    'label' => 'Powtórz hasło',
                    'attr' => [
                        'style' => 'margin-bottom: 50px;',
                    ]
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Podaj imię',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nazwisko',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Podaj nazwisko'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Podaj Email'
                ]
            ])
            ->add('phone', NumberType::class, [
                'label' => 'Telefon',
                'invalid_message' => 'Podaj same liczby',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Podaj telefon'
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Wyślij',
                'attr' => [
                    'class' => 'btn-primary mb-0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
