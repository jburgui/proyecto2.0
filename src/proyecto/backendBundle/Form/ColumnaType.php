<?php

namespace proyecto\backendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Columna 
 * @author Javier Burguillo Sánchez
 */

class ColumnaType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreColumna','text',array('label' => 'Nombre de la columna: '))
            ->add('tratamiento', 'choice', array(
                'label' => 'Tratamiento: ',
                'choices'   => array(
                    'media-aritmética'   => 'media-aritmética',
                    'media-geométrica'   => 'media-geométrica',
                    'media-armónica'   => 'media-armónica',
                    'sumatorio' => 'sumatorio',
                    'texto-fragmento'   => 'texto-fragmento',
                ),
            ))
            ->add('grafica', 'choice', array(
                'label' => 'Gráfica: ',
                'choices'   => array(
                    '0'   => 'No',
                    '1' => 'Si',
                ),
            ))
            //->add('idInforme')
            ->add('idSubpregunta','entity',array(
                'label' => 'Subpregunta: ', 
                'class' => 'proyectobackendBundle:Subpregunta', 
                'property' => 'subpregunta'
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
            'data_class' => 'proyecto\backendBundle\Entity\Columna'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'columna';
    }
}
