<?php

namespace proyecto\backendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Usuario
 * @author Javier Burguillo Sánchez
 */

class UsuarioType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre: ','attr' => array(
                    'required' => false
                )))
            ->add('contrasena', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'ERROR: Las contraseñas no coinciden.',
                'first_options'  => array('label' => 'Contraseña: '),
                'second_options' => array('label' => 'Repetir Contraseña: '),))
            ->add('rol','entity',array('label' => 'Rol: ', 'class' => 'proyectobackendBundle:Rol', 'property' => 'nombre'))
            ->add('email','text',array('label' => 'Email: '))
        ;
    }

    /**
     * Concreta algunas opciones del formulario
     * 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'proyecto\backendBundle\Entity\Usuario'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'proyecto_backendbundle_usuariotype';
    }
}
