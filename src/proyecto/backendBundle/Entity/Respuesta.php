<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad Respuesta
 * @author Javier Burguillo Sánchez
 */
class Respuesta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $respuesta;

    /**
     * @var \proyecto\backendBundle\Entity\Subpregunta
     */
    private $idSubpregunta;

    /**
     * @var \proyecto\backendBundle\Entity\Participante
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
     * Establecer respuesta
     *
     * @param string 
     * @return Respuesta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Obtener respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Establecer idSubpregunta
     *
     * @param \proyecto\backendBundle\Entity\Subpregunta 
     * @return Respuesta
     */
    public function setIdSubpregunta(\proyecto\backendBundle\Entity\Subpregunta $idSubpregunta = null)
    {
        $this->idSubpregunta = $idSubpregunta;
    
        return $this;
    }

    /**
     * Obtener idSubpregunta
     *
     * @return \proyecto\backendBundle\Entity\Subpregunta 
     */
    public function getIdSubpregunta()
    {
        return $this->idSubpregunta;
    }

    /**
     * Establecer idParticipante
     *
     * @param \proyecto\backendBundle\Entity\Participante 
     * @return Respuesta
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