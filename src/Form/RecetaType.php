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
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Entity\Foto;
use Doctrine\ORM\FotoRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
            $builder
                ->add('nombre')
                ->add('ingredientes')
                ->add('preparacion')
                ->add('dificultad')
                ->add('categoria')
                ->add('slug')
                //->add('created')/*
                /*->add('usuario', TextType::class, array(
                    'attr' => array(
                        'value' => $curid,
                        'placeholder' => $current,                   
                    )
                ))
               ->add('usuario', HiddenType::class, array(
                    'attr' => array(
                        'value' => $this->user->getId()
                    )))*/
                ->add('usuario', EntityType::class, array(
                    'class' => 'App:Usuario',
                    'query_builder' => function (EntityRepository $er){
                        return $er->createQueryBuilder('u')
                            ->where('u.id ='.$this->user->getId().'');
                    },
                    'choice_label' => 'apodo',
                ))
                ->add('ruta', FileType::class, array(
                    'label' => 'Imagen destacada',
                    'required' => false
                ))
                ;    
        }
        else {

            $builder
                ->add('nombre')
                ->add('ingredientes')
                ->add('preparacion')
                ->add('dificultad')
                //->add('created')
                ->add('usuario') 
                ->add('categoria')
                ->add('nombreimagen', TextType::class, array(
                    'label' => 'Título de la imagen',
                    'required' => false
                ))
                ->add('ruta', FileType::class, array(
                    'label' => 'Imagen destacada',
                    'required' => false
                ))
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
