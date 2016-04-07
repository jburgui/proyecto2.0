<?php

namespace proyecto\backendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use proyecto\backendBundle\Entity\Subpregunta;

/**
 * Formulario Pregunta
 * @author Javier Burguillo Sánchez
 */

class PreguntaType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numPregunta','integer',array('label' => 'Número de pregunta: '))
            ->add('pregunta','text',array('label' => 'Pregunta: '))
            ->add('numSubpreguntas','integer',array('label' => 'Número de Subpreguntas: ','data' => '0','read_only' => true,))
            ->add('repetirPregunta','integer',array('label' => 'Repetir pregunta: ','data' => '0'))
            //->add('idParte')
            ->add('subpreguntas', 'collection', array(
                'type'         => new SubpreguntaType(),
                'label' => 'Subpreguntas (mínimo 1): ',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new Subpregunta(),
                'prototype_name' => 'Subpregunta__numsubpregunta__',
                'attr' => array(
                    'class' => 'row subpreguntas'
                )
            ))
        ;
    }

    /**
     * Concreta algunas opciones del formulario
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'proyecto\backendBundle\Entity\Pregunta'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'pregunta';
    }
}
