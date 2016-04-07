<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad TiempoRespuesta
 * @author Javier Burguillo Sánchez
 */
class TiempoRespuesta
{
    
    /**
     * @var integer
     */
    private $id;


    /**
     * @var float
     */
    private $tiempoRespuesta;

    /**
     * @var \proyecto\frontendBundle\Entity\idPregunta
     */
    private $idPregunta;
    
    /**
     * @var \proyecto\frontendBundle\Entity\idParticipante
     */
    private $idParticipante;


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
     * Establecer idPregunta
     *
     * @param \proyecto\frontendBundle\Entity\Pregunta 
     * @return DatosAsociados
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
     * Establecer tiempoRespuesta
     *
     * @param float 
     * @return TiempoRespuesta
     */
    public function setTiempoRespuesta($tiempoRespuesta)
    {
        $this->tiempoRespuesta = $tiempoRespuesta;
    
        return $this;
    }

    /**
     * Obtener tiempoRespuesta
     *
     * @return float 
     */
    public function getTiempoRespuesta()
    {
        return $this->tiempoRespuesta;
    }
    
    
    /**
     * Establecer idParticipante
     *
     * @param \proyecto\frontendBundle\Entity\articipante 
     * @return DatosAsociados
     */
    public function setIdParticipante(\proyecto\frontendBundle\Entity\Participante $idParticipante = null)
    {
        $this->idParticipante = $idParticipante;
    
        return $this;
    }

    /**
     * Obtener idParticipante
     *
     * @return \proyecto\frontendBundle\Entity\Participante
     */
    public function getIdParticipante()
    {
        return $this->idParticipante;
    }   
    
}