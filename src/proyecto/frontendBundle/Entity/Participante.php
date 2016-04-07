<?php

namespace proyecto\frontendBundle\Entity;


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