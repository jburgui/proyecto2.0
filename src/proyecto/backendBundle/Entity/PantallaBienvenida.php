<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad PantallaBienvenida
 * @author Javier Burguillo Sánchez
 */
class PantallaBienvenida
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idParteEstudio;

    /**
     * @var string
     */
    private $mensaje;


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
     * Establecer idParte
     *
     * @param integer 
     * @return PantallaBienvenida
     */
    public function setIdParteEstudio(\proyecto\backendBundle\Entity\ParteEstudio $idParteEstudio = null)
    {
        $this->idParteEstudio = $idParteEstudio;
    
        return $this;
    }

    /**
     * Obtener idParte
     *
     * @return integer 
     */
    public function getIdParteEstudio()
    {
        return $this->idParteEstudio;
    }

    /**
     * Establecer mensaje
     *
     * @param string 
     * @return PantallaBienvenida
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    
        return $this;
    }

    /**
     * Obtener mensaje
     *
     * @return string 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }
}