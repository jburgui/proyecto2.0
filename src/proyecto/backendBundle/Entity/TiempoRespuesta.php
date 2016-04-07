<?php

namespace proyecto\backendBundle\Entity;


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
     * @var \proyecto\backendBundle\Entity\idPregunta
     */
    private $idPregunta;
    
    /**
     * @var \proyecto\backendBundle\Entity\idParticipante
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
     * @param \proyecto\backendBundle\Entity\Pregunta 
     * @return DatosAsociados
     */
    public function setIdPregunta(\proyecto\backendBundle\Entity\Pregunta $idPregunta = null)
    {
        $this->idPregunta = $idPregunta;
    
        return $this;
    }

    /**
     * Obtener idPregunta
     *
     * @return \proyecto\backendBundle\Entity\Pregunta
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
     * @param \proyecto\backendBundle\Entity\articipante 
     * @return DatosAsociados
     */
    public function setIdParticipante(\proyecto\backendBundle\Entity\Participante $idParticipante = null)
    {
        $this->idParticipante = $idParticipante;
    
        return $this;
    }

    /**
     * Obtener idParticipante
     *
     * @return \proyecto\backendBundle\Entity\Participante
     */
    public function getIdParticipante()
    {
        return $this->idParticipante;
    }   
    
}