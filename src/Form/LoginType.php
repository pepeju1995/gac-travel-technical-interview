<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'attr' => [
                    'class' => 'floating-input form-control', 
                    'placeholder' => ''
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'floating-input form-control', 
                    'placeholder'=> ''
                ]
            ])
            ->add('rememberMe', CheckboxType::class, [
                'label' => 'Mantener mi sesion activa',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
