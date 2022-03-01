<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MovieType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $email = $user->getemail();
       
        $builder
        ->add('Title',TextType::class,array(
            'label_attr' => array('class' => 'form-label'),
            'attr' => array('class' => 'form-control')
        ))
        ->add('Description',TextType::class,array(
            'label_attr' => array('class' => 'form-label'),
            'attr' => array('class' => 'form-control')
        ))
        ->add('Name_of_the_user',TextType::class,array(
            'data' => $email,
            'label_attr' => array('class' => 'form-label'),
            'attr' => array('class' => 'form-control')
        ))
        ->add('Date_of_publication',NULL,array(
            'label_attr' => array('class' => 'form-label'),
            'attr' => array('class' => 'form-control')
        ))
        ->add('Likes',HiddenType::class,array(
            'data' => 0
        ))
        ->add('Hates',HiddenType::class,array(
            'data' => 0
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
