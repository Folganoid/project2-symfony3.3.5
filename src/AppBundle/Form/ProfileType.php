<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 21.07.17
 * Time: 10:38
 */
class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'attr' => [
                'value' => $options['username'],
            ]
        ))
            ->add('email', EmailType::class, array(
                'attr' => [
                    'value' => $options['email'],
                ]
            ))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => array('label' => 'New password'),
                'second_options' => array('label' => 'Repeat password'),
                'required' => false,
            ])
            ->add('role', ($options['field']) ? IntegerType::class : HiddenType::class, array(
                'attr' => [
                    'value' => $options['role'],
                ],
            ))
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'uk-button uk-button-default uk-align-right"'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'username' => null,
            'email' => null,
            'role' => null,
            'field' => null,
        ]);
    }
}