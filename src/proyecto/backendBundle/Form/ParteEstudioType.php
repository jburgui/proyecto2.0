<?php

namespace proyecto\backendBundle\Form;
use proyecto\backendBundle\Entity\PantallaBienvenida;
use proyecto\backendBundle\Entity\Pregunta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario ParteEstudio
 * @author Javier Burguillo Sánchez
 */

class ParteEstudioType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre: '))
            ->add('numOrden','integer',array('label' => 'Número de orden: ','data' => '0'))
            ->add('numPreguntas','integer',array('label' => 'Número de preguntas: ','data' => '0','read_only' => true,))
            ->add('titulo','text',array('label' => 'Título: '))
            ->add('guardarTiempoRespuesta','checkbox',array('label' => 'Guardar tiempo de respuesta: '))
            ->add('numPreguntasDePrueba','integer',array('label' => 'Número de preguntas de prueba(opcional): ','data' => '0'))
            //->add('idEstudio')
            ->add('idTipoPregunta','entity',array('label' => 'Tipo de pregunta: ', 'class' => 'proyectobackendBundle:TipoPregunta',
                'property' => 'nombre','attr' => array(
                    "onchange" => "javascript:ocultarnumPreguntasDePrueba(this.id);")))
            ->add('pantallasBienvenida', 'collection', array(
                'type'         => new PantallaBienvenidaType(),
                'label' => 'Mensaje de Bienvenida(opcional):',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new PantallaBienvenida(),
                'prototype_name' => 'pantalla__numpantalla__',
                'attr' => array(
                    'class' => 'row pantallas'
                )
            ))
            ->add('preguntas', 'collection', array(
                'type'         => new PreguntaType(),
                'label' => 'Preguntas (mínimo 1): ',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new Pregunta(),
                'prototype_name' => 'pregunta__numpregunta__',
                'attr' => array(
                    'class' => 'row preguntas'
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
            'data_class' => 'proyecto\backendBundle\Entity\ParteEstudio'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'Parte';
    }
}
