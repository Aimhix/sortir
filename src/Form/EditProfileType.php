<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('phone', TelType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^0[1-9]\d{8}$/',
                        'message' => 'Le numéro de téléphone ne respecte pas un bon format, par exemple : 0123456789.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['readonly' => true],])
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
