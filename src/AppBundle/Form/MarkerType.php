<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 04.08.17
 * Time: 19:05
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MarkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('coordX', null, array('label' => 'Latitude'))
            ->add('coordY', null, array('label' => 'Longitude'))
            ->add('save', SubmitType::class, [
            'attr' => [
        'class' => 'uk-button uk-button-default uk-align-right'
    ]
            ])
        ;
    }
}