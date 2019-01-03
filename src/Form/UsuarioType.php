<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('Nombre', TextType::class)
            ->add('Apellidos', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password', 'attr' => array('class' =>'dspl')),
                'second_options' => array('label' => 'Repeat Password', 'attr' => array('class' =>'dspl'))
            ))
            //->add('apodo', TextType::class)
            /*->add('isactive', ChoiceType::class, 
                array('choices' => array(                 
                    'Yes' => '1',
                    'No' => '0'   
                )  
            ))*/
            //->add('created')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
