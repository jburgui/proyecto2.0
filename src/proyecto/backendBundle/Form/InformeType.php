<?php

namespace proyecto\backendBundle\Form;
use proyecto\backendBundle\Entity\Columna;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Informe
 * @author Javier Burguillo Sánchez
 */

class InformeType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tituloGrafico','text',array('label' => 'Título del gráfico: '))
            ->add('idParteEstudio','entity',array('label' => 'Seleccione una parte: ', 'class' => 'proyectobackendBundle:ParteEstudio', 'property' => 'nombre'))
            ->add('columnas', 'collection', array(
                'type'         => new ColumnaType(),
                'label' => 'Columnas (Mínimo 1): ',
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype' => new Columna(),
                'prototype_name' => 'columna__numcolumna__',
                'attr' => array(
                    'class' => 'row columnas'
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
            'data_class' => 'proyecto\backendBundle\Entity\Informe'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'informe';
    }
}
