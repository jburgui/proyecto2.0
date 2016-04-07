<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad TipoRespuesta
 * @author Javier Burguillo Sánchez
 */
class TipoRespuesta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;


    /**
     * Obtener id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Establecer nombre
     *
     * @param string 
     * @return TipoRespuesta
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Obtener nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}