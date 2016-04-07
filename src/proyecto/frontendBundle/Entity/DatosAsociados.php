<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad DatosAsociados
 * @author Javier Burguillo Sánchez
 */
class DatosAsociados
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idPregunta;

    /**
     * @var \proyecto\frontendBundle\Entity\Fragmento
     */
    private $idFragmento;

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
     * Establecer idPregunta
     *
     * @param integer 
     * @return DatosAsociados
     */
    public function setIdPregunta($idPregunta)
    {
        $this->idPregunta = $idPregunta;
    
        return $this;
    }

    /**
     * Obtener idPregunta
     *
     * @return integer 
     */
    public function getIdPregunta()
    {
        return $this->idPregunta;
    }

    /**
     * Establecer idFragmento
     *
     * @param \proyecto\frontendBundle\Entity\Fragmento 
     * @return DatosAsociados
     */
    public function setIdFragmento(\proyecto\frontendBundle\Entity\Fragmento $idFragmento = null)
    {
        $this->idFragmento = $idFragmento;
    
        return $this;
    }

    /**
     * Obtener idFragmento
     *
     * @return \proyecto\frontendBundle\Entity\Fragmento 
     */
    public function getIdFragmento()
    {
        return $this->idFragmento;
    }

    /**
     * Establecer idParticipante
     *
     * @param \proyecto\frontendBundle\Entity\Participante 
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