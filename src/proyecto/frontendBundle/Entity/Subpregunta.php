<?php

namespace proyecto\frontendBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Entidad Subpregunta
 * @author Javier Burguillo Sánchez
 */
class Subpregunta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numSubpregunta;

    /**
     * @var string
     */
    private $subpregunta;

    /**
     * @var \proyecto\frontendBundle\Entity\Pregunta
     */
    private $idPregunta;

    /**
     * @var \proyecto\frontendBundle\Entity\TipoRespuesta
     */
    private $idTipoRespuesta;

    /**
     * @var ArrayCollection \proyecto\frontendBundle\Entity\rangoRespuesta
     */
    protected $rangosRespuesta;
    
    /**
     * @var ArrayCollection \proyecto\frontendBundle\Entity\opcionRespuesta
     */
    protected $opcionesRespuesta;
    
    public function __construct()
    {
        $this->rangosRespuesta = new ArrayCollection();
        $this->opcionesRespuesta = new ArrayCollection();
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
     * Establecer numSubpregunta
     *
     * @param integer 
     * @return Subpregunta
     */
    public function setNumSubpregunta($numSubpregunta)
    {
        $this->numSubpregunta = $numSubpregunta;
    
        return $this;
    }

    /**
     * Obtener numSubpregunta
     *
     * @return integer 
     */
    public function getNumSubpregunta()
    {
        return $this->numSubpregunta;
    }

    /**
     * Establecer subpregunta
     *
     * @param string 
     * @return Subpregunta
     */
    public function setSubpregunta($subpregunta)
    {
        $this->subpregunta = $subpregunta;
    
        return $this;
    }

    /**
     * Obtener subpregunta
     *
     * @return string 
     */
    public function getSubpregunta()
    {
        return $this->subpregunta;
    }

    /**
     * Establecer idPregunta
     *
     * @param \proyecto\frontendBundle\Entity\Pregunta 
     * @return Subpregunta
     */
    public function setIdPregunta(\proyecto\frontendBundle\Entity\Pregunta $idPregunta = null)
    {
        $this->idPregunta = $idPregunta;
    
        return $this;
    }

    /**
     * Obtener idPregunta
     *
     * @return \proyecto\frontendBundle\Entity\Pregunta 
     */
    public function getIdPregunta()
    {
        return $this->idPregunta;
    }

    /**
     * Establecer idTipoRespuesta
     *
     * @param \proyecto\frontendBundle\Entity\TipoRespuesta 
     * @return Subpregunta
     */
    public function setIdTipoRespuesta(\proyecto\frontendBundle\Entity\TipoRespuesta $idTipoRespuesta = null)
    {
        $this->idTipoRespuesta = $idTipoRespuesta;
    
        return $this;
    }

    /**
     * Obtener idTipoRespuesta
     *
     * @return \proyecto\frontendBundle\Entity\TipoRespuesta 
     */
    public function getIdTipoRespuesta()
    {
        return $this->idTipoRespuesta;
    }
    
    /**
     * Establecer RangosRespuesta
     *
     * @param ArrayCollection 
     * @return Subpregunta
     */
    public function setRangosRespuesta($rangosRespuesta = null)
    {
        $this->rangosRespuesta = $rangosRespuesta;
        foreach ($rangosRespuesta as $rangoRespuesta) {
            $rangoRespuesta->setIdSubpregunta($this);
        }
        return $this;
    }
    
    /**
     * Obtener RangosRespuesta
     *
     * @return ArrayCollection 
     */
    public function getRangosRespuesta()
    {
        return $this->rangosRespuesta;
    }
    
    /**
     * Establecer OpcionesRespuesta
     *
     * @param ArrayCollection 
     * @return Subpregunta
     */
    public function setOpcionesRespuesta($opcionesRespuesta = null)
    {
        $this->opcionesRespuesta = $opcionesRespuesta;
        foreach ($opcionesRespuesta as $opcionRespuesta) {
            $opcionRespuesta->setIdSubpregunta($this);
        }
        return $this;
    }
    
    /**
     * Obtener OpcionesRespuesta
     *
     * @return ArrayCollection 
     */
    public function getOpcionesRespuesta()
    {
        return $this->opcionesRespuesta;
    }
    
    /**
     * Método mágico. Devuelve el campo irdentificador principal para que no sea el id
     *
     * @return string 
     */
    public function __toString (){
        return $this->subpregunta;
    }
}