<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\Location;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ActivityType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,  [
                'label' => 'Nature de la sortie : ',
            ])
            ->add('dateStart',null,  [
        'label' => 'Date et heure de début de la sortie : ',
            ])
            ->add('duration',null,  [
                'label' => 'Durée de la sortie : ',
            ])
            ->add('subLimitDate',null,  [
                'label' => 'Date et heure limite d\'inscription : ',
            ])
            ->add('subMax',null,  [
                'label' => 'Nombre maximum de participant : ',
            ])
            ->add('infoActivity',null,  [
                'label' => 'Infos suplémentaire : ',
            ])
            ->add('isPublished',null,  [
                'label' => 'Publier la sortie : ',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label'=> 'Lieu de la sortie',
                'placeholder' => 'Selectionnez un lieu  '
            ])
            ->add("save",SubmitType::class,["label"=>"Valider"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
