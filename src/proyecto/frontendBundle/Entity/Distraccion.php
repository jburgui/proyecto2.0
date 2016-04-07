<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad Distraccion
 * @author Javier Burguillo Sánchez
 */
class Distraccion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numOrden;

    /**
     * @var \proyecto\frontendBundle\Entity\Estudio
     */
    private $idEstudio;


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
     * Establecer numOrden
     *
     * @param integer 
     * @return Distraccion
     */
    public function setNumOrden($numOrden)
    {
        $this->numOrden = $numOrden;
    
        return $this;
    }

    /**
     * Obtener numOrden
     *
     * @return integer 
     */
    public function getNumOrden()
    {
        return $this->numOrden;
    }

    /**
     * Establecer idEstudio
     *
     * @param \proyecto\frontendBundle\Entity\Estudio 
     * @return Distraccion
     */
    public function setIdEstudio(\proyecto\frontendBundle\Entity\Estudio $idEstudio = null)
    {
        $this->idEstudio = $idEstudio;
    
        return $this;
    }

    /**
     * Obtener idEstudio
     *
     * @return \proyecto\frontendBundle\Entity\Estudio 
     */
    public function getIdEstudio()
    {
        return $this->idEstudio;
    }
    
    /**
     * Método mágico. Devuelve el campo irdentificador principal para que no sea el id
     *
     * @return string 
     */
    public function __toString (){
        return $this->nombre;
    }
}
