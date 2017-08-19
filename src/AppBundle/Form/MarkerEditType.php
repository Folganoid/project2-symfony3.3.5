<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 04.08.17
 * Time: 19:05
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MarkerType
 * @package AppBundle\Form
 */
class MarkerEditType extends AbstractType

{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, array(
                    'attr' => [
                        'value' => $options['name'],
                    ]
                )
            )
            ->add('coordX', TextType::class, array(
                    'attr' => [
                        'value' => $options['coordX'],
                    ]
                )
            )
            ->add('coordY', TextType::class, array(
                    'attr' => [
                        'value' => $options['coordY'],
                    ]
                )
            )

            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'uk-button uk-button-default uk-align-right'
                ]
            ])
            ->add('delete', SubmitType::class, [
                'attr' => [
                    'class' => 'uk-button uk-button-danger uk-align-right'
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'AppBundle\Entity\Marker',
                'name' => null,
                'coordX' => null,
                'coordY' => null,
        ]);
    }
}