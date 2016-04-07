<?php

namespace proyecto\frontendBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Entidad Estudio
 * @author Javier Burguillo Sánchez
 */
class Estudio
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
     * @var integer
     */
    //indica el número de partes y distracciones en conjunto
    private $numSecciones;

    /**
     * @var integer
     */
    private $numDatosEstudio;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var boolean
     */
    private $activa = 0;

    /**
     * @var ArrayCollection
     */
    private $partesEstudio;
    
    /**
     * @var ArrayCollection
     */
    private $distracciones;
    
    public function __construct()
    {
        $this->partesEstudio = new ArrayCollection();
	$this->distracciones = new ArrayCollection();
    }
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
     * @return Estudio
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

    /**
     * Establecer numSecciones
     *
     * @param integer 
     * @return Estudio
     */
    public function setNumSecciones($numSecciones)
    {
        $this->numSecciones = $numSecciones;
    
        return $this;
    }

    /**
     * Obtener numSecciones
     *
     * @return integer 
     */
    public function getNumSecciones()
    {
        return $this->numSecciones;
    }

    /**
     * Establecer numDatosEstudio
     *
     * @param integer 
     * @return Estudio
     */
    public function setNumDatosEstudio($numDatosEstudio)
    {
        $this->numDatosEstudio = $numDatosEstudio;
    
        return $this;
    }

    /**
     * Obtener numDatosEstudio
     *
     * @return integer 
     */
    public function getNumDatosEstudio()
    {
        return $this->numDatosEstudio;
    }

    /**
     * Establecer descripcion
     *
     * @param string 
     * @return Estudio
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Obtener descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Establecer activa
     *
     * @param boolean 
     * @return Estudio
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;
    
        return $this;
    }

    /**
     * Obtener activa
     *
     * @return boolean 
     */
    public function getActiva()
    {
        return $this->activa;
    }
    
    /**
     * Establecer PartesEstudio
     *
     * @param ArrayCollection 
     * @return Estudio
     */    
    public function setPartesEstudio(ArrayCollection  $partesEstudio = null)
    {
        $this->partesEstudio = $partesEstudio;
        foreach ($partesEstudio as $parteEstudio) {
            $parteEstudio->setIdEstudio($this);
        }
        return $this;
    }
    
    /**
     * Obtener PartesEstudio
     *
     * @return ArrayCollection 
     */
    public function getPartesEstudio()
    {
        return $this->partesEstudio;
    }
    
    /**
     * Establecer Distracciones
     *
     * @param ArrayCollection 
     * @return Estudio
     */	
    public function setDistracciones(ArrayCollection  $distracciones = null)
    {
        $this->distracciones = $distracciones;
        foreach ($distracciones as $distraccion) {
            $distraccion->setIdEstudio($this);
        }
        return $this;
    }
    
    /**
     * Obtener Distracciones
     *
     * @return ArrayCollection 
     */
    public function getDistracciones()
    {
        return $this->distracciones;
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