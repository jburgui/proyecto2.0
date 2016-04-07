<?php

namespace proyecto\backendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador por Defecto (al entrar en la zona privada) 
 * @author Javier Burguillo Sánchez
 */
class DefaultController extends Controller
{
    /**
     * Muestra la zona privada solo con el menú
     * @return vistaIndex 
     */
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $admin=true;}
        else{
            $admin=false;
        }
        
        return $this->render('proyectobackendBundle:Default:index.html.twig', array(
            'admin' => $admin,
        ));
        

    }

}
