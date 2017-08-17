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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class PictureType
 * @package AppBundle\Form
 */
class ImgEditType extends AbstractType

{
    private $tokenStorage;

    /**
     * PictureType constructor.
     * @param TokenStorageInterface|null $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
    }

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
            ->add('markerId', EntityType::class,
                array(
                    'class' => 'AppBundle:Marker',
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->where('m.userId = ' . $this->tokenStorage->getToken()->getUser()->getId())
                            ->orderBy('m.name', 'ASC');
                    },
                    'label' => 'Marker',
                    'required' => true,
                )
            )
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'uk-button uk-button-default uk-align-right'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'AppBundle\Entity\Picture',
                'id' => null,
                'name' => null,
                'markerId' => null,
        ]);
    }
}