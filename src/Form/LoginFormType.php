<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Login',
                'error_mapping' => true,
                'attr' => [
                    'placeholder' => 'Podaj login'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Hasło',
                'error_mapping' => true,
            ])
            ->add('sendButton', SubmitType::class, [
                'label' => 'Wyślij',
                'attr' => [
                    'class' => 'btn-primary mb-0',
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
