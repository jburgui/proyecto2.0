<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad Participante
 * @author Javier Burguillo Sánchez
 */
class Participante
{
    
    /**
     * @var integer
     */
    private $id;


    /**
     * Obtener id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}