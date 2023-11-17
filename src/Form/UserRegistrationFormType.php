<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname', TextType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Role Admin' => 'ROLE_ADMIN',
                    'Role User' => 'ROLE_USER',
                ],
                'expanded' => true, // Pour afficher les boutons radio au lieu d'une liste déroulante
                'multiple' => false, // Pour autoriser la sélection d'un seul rôle
            ])
            ->add('lastname', TextType::class)
            ->add('phone', TelType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class,)
            ->add('confirmpassword', PasswordType::class, [
                'label' => 'Confirmer mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password']])
            ->add('campus')
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User',
        ]);
    }
}