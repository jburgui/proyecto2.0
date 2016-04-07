<?php

namespace proyecto\backendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use proyecto\backendBundle\Entity\RangoRespuesta;
use proyecto\backendBundle\Entity\OpcionRespuesta;

/**
 * Formulario Subpregunta 
 * @author Javier Burguillo Sánchez
 */

class SubpreguntaType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numSubpregunta','integer',array('label' => 'Número de Subpregunta: ','data' => '0','read_only' => false,))
            ->add('subpregunta','text',array('label' => 'Subpregunta: '))
            //->add('idPregunta')
            ->add('idTipoRespuesta','entity',array(
                'label' => 'Tipo de respuesta: ', 'class' => 'proyectobackendBundle:TipoRespuesta', 'property' => 'nombre')) 
            ->add('rangosRespuesta', 'collection', array(
                'type'         => new RangoRespuestaType(),
                'label' => 'Rango numérico: ',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new RangoRespuesta(),
                'prototype_name' => 'RangoRespuesta__numRangoRespuesta__',
                'attr' => array(
                    'class' => 'row rangosRespuesta'
                )
            ))
            ->add('opcionesRespuesta', 'collection', array(
                'type'         => new OpcionRespuestaType(),
                'label' => 'Opciones: ',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new OpcionRespuesta(),
                'prototype_name' => 'OpcionRespuesta__numOpcionRespuesta__',
                'attr' => array(
                    'class' => 'row opcionesRespuesta'
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
            'data_class' => 'proyecto\backendBundle\Entity\Subpregunta'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'proyecto_backendbundle_subpreguntatype';
    }
}
