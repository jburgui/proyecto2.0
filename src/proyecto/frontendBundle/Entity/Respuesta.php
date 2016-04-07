<?php

namespace proyecto\frontendBundle\Entity;


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
     * @var \proyecto\frontendBundle\Entity\Subpregunta
     */
    private $idSubpregunta;

    /**
     * @var \proyecto\frontendBundle\Entity\Participante
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
     * @param \proyecto\frontendBundle\Entity\Subpregunta 
     * @return Respuesta
     */
    public function setIdSubpregunta(\proyecto\frontendBundle\Entity\Subpregunta $idSubpregunta = null)
    {
        $this->idSubpregunta = $idSubpregunta;
    
        return $this;
    }

    /**
     * Obtener idSubpregunta
     *
     * @return \proyecto\frontendBundle\Entity\Subpregunta 
     */
    public function getIdSubpregunta()
    {
        return $this->idSubpregunta;
    }

    /**
     * Establecer idParticipante
     *
     * @param \proyecto\frontendBundle\Entity\Participante 
     * @return Respuesta
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