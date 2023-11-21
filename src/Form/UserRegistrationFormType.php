<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          //  ->add('pseudo', TextType::class) pour enlever pseudo de l'inscription manuelle
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('phone', TelType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class,)
            ->add('confirmpassword', PasswordType::class, [
                'label' => 'Confirmer mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password']])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder' => 'Choix',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false]);

    }


}