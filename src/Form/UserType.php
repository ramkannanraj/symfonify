<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $url = '/users/add';
        $builder
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('groups', EntityType::class, [
               'class'  => Group::class,
               'multiple' => true,
               'label' => 'Group',
               'attr' => ['class' => 'form-control'],
            ])
            ->setAction($url)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
