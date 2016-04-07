<?php

namespace proyecto\backendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Adjetivo 
 * @author Javier Burguillo Sánchez
 */

class AdjetivoType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre: '))
            ->add('tipo','text',array('label' => 'Tipo: '))
            ->add('frecTeorica','text',array('label' => 'Frecuencia teórica: '))
            ->add('numLetras','text',array('label' => 'Número de letras: '))
            ->add('numSilabas','text',array('label' => 'número de sílabas: '))
            ->add('categoria','text',array('label' => 'Categoría: '))
            ->add('frecUsoComoAdj','text',array('label' => 'Frecuencia de uso como adjetivo: '))
            ->add('numSignificados','text',array('label' => 'Número de significados: '))
        ;
    }
    /**
     * Concreta algunas opciones del formulario
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'proyecto\backendBundle\Entity\Adjetivo'
        ));
    }
    
    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'proyecto_backendbundle_adjetivotype';
    }
}
