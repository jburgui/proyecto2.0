<?php

namespace proyecto\backendBundle\Form;
use proyecto\backendBundle\Entity\ParteEstudio;
use proyecto\backendBundle\Entity\Distraccion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Estudio 
 * @author Javier Burguillo Sánchez
 */

class EstudioType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre: '))
            ->add('numSecciones','integer',array('label' => 'Número de secciones: ','data' => '0','read_only' => true,))
            ->add('numDatosEstudio','integer',array('label' => 'Número de datos de estudio: ','data' => '0'))
            ->add('descripcion','text',array('label' => 'Descripción: '))
            ->add('partesEstudio', 'collection', array(
                'type'         => new ParteEstudioType(),
                'label' => 'Partes (mínimo 1):',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new ParteEstudio(),
                'prototype_name' => 'parte__numparte__',
                'attr' => array(
                    'class' => 'row partes'
                )
            ))
            ->add('distracciones', 'collection', array(
                'type'         => new DistraccionType(),
                'label' => 'Distracciones:',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new Distraccion(),
                'prototype_name' => 'distraccion__numdistraccion__',
                'attr' => array(
                    'class' => 'row distracciones'
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
            'data_class' => 'proyecto\backendBundle\Entity\Estudio'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'Estudio';
    }
}
