<?php

namespace App\Form;

use App\Entity\Foto;
use App\Entity\Receta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('ruta', FileType::class, array(
                'label' => 'Subir foto',
            ))
            //->add('created')
            ->add('receta_id', EntityType::class, array(
                'class' =>  Receta::class,
                'choice_label' => 'nombre',
                'choice_value' => 'id'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Foto::class,
        ]);
    }
}
