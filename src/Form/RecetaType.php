<?php

namespace App\Form;

use App\Entity\Receta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Usuario;

class RecetaType extends AbstractType
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->user != 'admin') 
        {
            $current = $this->user->getApodo();
            $curid = $this->user->getId();            

            print_r('current '.$current.'-'.$curid);
            $builder
                ->add('nombre')
                ->add('ingredientes')
                ->add('preparacion')
                ->add('dificultad')
                //->add('created')
                /*->add('usuario', TextType::class, array(
                    'attr' => array(
                        'value' => $curid,
                        'placeholder' => $current,                   
                    )
                ))*/
               ->add('usuario', HiddenType::class, array(
                    'attr' => array(
                        'value' => $this->user->getId()
                    ))) 
                ->add('categoria')
            ;
        }
        else {

            $builder
                ->add('nombre')
                ->add('ingredientes')
                ->add('preparacion')
                ->add('dificultad')
                //->add('created')
                ->add('usuario', HiddenType::class) 
                ->add('categoria')
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       
        $resolver->setDefaults([
            'data_class' => Receta::class,
        ]);

    }
}
