<?php

namespace proyecto\backendBundle\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Formulario Fragmento 
 * @author Javier Burguillo Sánchez
 */

class FragmentoType extends AbstractType
{
    /**
     * Método que construye el formulario
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre (letras y "_" separadas por espacios): '))
            ->add('primeraLetra','checkbox',array('label' => 'Primera letra: '))
            ->add('ultimaLetra','checkbox',array('label' => 'Última letra: ','required'  => false ))
            ->add('dosEspaciosJuntos','checkbox',array('label' => 'Dos es pacios juntos:','required'  => false))
            ->add('tresLetrasJuntas','checkbox',array('label' => 'Tres letras juntas: ','required'  => false))
            ->add('ratioDadasEliminadas','text',array('label' => 'Ratio dadas/eliminadas: '))
            ->add('letrasDadas','text',array('label' => 'Letras dadas: '))
            ->add('ratioVocalesConsonantes','text',array('label' => 'Ratio vocales/consonantes: '))
            ->add('idAdjetivo','entity',array(
                'label' => 'Adjetivo asociado: ', 
                'class' => 'proyectobackendBundle:Adjetivo', 
                'property' => 'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nombre', 'ASC');
                },
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
            'data_class' => 'proyecto\backendBundle\Entity\Fragmento'
        ));
    }

    /**
     * Devuelve la primera parte del nombre que tendrán los campos del formulario
     * 
     * @return string 
     */
    public function getName()
    {
        return 'proyecto_backendbundle_fragmentotype';
    }
}
