<?php

namespace App\Form;


use App\DTO\ActivitySearchDTO;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ActivitySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Que recherches-tu ?'
                ],
            ])
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                // 'format' => 'yyyy-MM-dd', // format par défaut, à rétablir si ça foire
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'dateStart',
//                    'min' => (new \DateTime())->format('Y-m-d'), // J'empêche la sélection de dates antérieures à aujourd'hui
                ],
            ])
            ->add('subLimitDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                // 'format' => 'yyyy-MM-dd',
//                'data' => (new \DateTime())->modify('+7 days'), // Je prend la date d'aujourd'hui et je lui ajoute 7 jours
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'),
//                    'placeholder' => 'Date de fin',
                    'id' => 'subLimitDate',
                ],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder' => 'Campus',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
//            ->add('submit', SubmitType::class, [
//                'attr' => [
//                    'class' => 'btn btn-primary'
//                ],
//            ])
            ->add('organizer', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties que j\'organise',
            ])

            //
            // "Sorties auxquelles je suis inscrit(e)"
            ->add('isRegistered', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je suis inscrit(e)',
            ])

            //
            // "Sorties auxquelles je ne suis pas inscrit(e)"
            ->add('isNotRegistered', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit(e)',
            ])

            // "Sorties passées"
            ->add('isPast', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées',
            ]);
        ;}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivitySearchDTO::class,
        ]);
    }
}