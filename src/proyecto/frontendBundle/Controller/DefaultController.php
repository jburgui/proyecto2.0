<?php

namespace proyecto\frontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador por Defecto que se encarga de la página principal
 * @author Javier Burguillo Sánchez
 */
class DefaultController extends Controller
{
    /**
     * Cargar la página principal
     * @return vistaIndex 
     */
    public function indexAction()
    {
        $session = $this->getRequest()->getSession();
        $session->clear();
        return $this->render('proyectofrontendBundle:Default:index.html.twig');
    }
}

?>