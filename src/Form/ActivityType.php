<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateStart')
            ->add('duration')
            ->add('subLimitDate')
            ->add('subMax')
            ->add('infoActivity')
            ->add('isPublished')
            ->add('status',EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'wording',
            ])
//           ->add('location', EntityType::class, [
//               'class' => Location::class,
//               '' => 'name',
//               'label' => 'streetName',
//               'label' => 'latitude',
//               'label' => 'longitude',
//               'choice_label' => 'cities',
//           ])
        //    ->add('campus')
       //     ->add('organizer')
        //    ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
