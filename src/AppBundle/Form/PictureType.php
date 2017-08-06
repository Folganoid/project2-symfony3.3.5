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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('markerId', EntityType::class,
                array(
                    'class' => 'AppBundle:Marker',
                    'choice_label' => 'name',
                    'label' => 'Marker'
                )
            )
            ->add('filename', FileType::class)
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'uk-button uk-button-default uk-align-right"'
                ]
            ]);
    }
}