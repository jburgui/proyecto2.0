<?php

namespace proyecto\backendBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Entidad Pregunta
 * @author Javier Burguillo Sánchez
 */
class Pregunta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numPregunta;

    /**
     * @var string
     */
    private $pregunta;

    /**
     * @var integer
     */
    private $numSubpreguntas;

    /**
     * @var integer
     */
    private $repetirPregunta;

    /**
     * @var \proyecto\backendBundle\Entity\ParteEstudio
     */
    private $idParteEstudio;

    /**
     * @var ArrayCollection \proyecto\backendBundle\Entity\Subpregunta
     */
    private $subpreguntas;
    
    
    public function __construct()
    {
        $this->subpreguntas = new ArrayCollection();
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
     * Establecer numPregunta
     *
     * @param integer 
     * @return Pregunta
     */
    public function setNumPregunta($numPregunta)
    {
        $this->numPregunta = $numPregunta;
    
        return $this;
    }

    /**
     * Obtener numPregunta
     *
     * @return integer 
     */
    public function getNumPregunta()
    {
        return $this->numPregunta;
    }

    /**
     * Establecer pregunta
     *
     * @param string 
     * @return Pregunta
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;
    
        return $this;
    }

    /**
     * Obtener pregunta
     *
     * @return string 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Establecer numSubpreguntas
     *
     * @param integer 
     * @return Pregunta
     */
    public function setNumSubpreguntas($numSubpreguntas)
    {
        $this->numSubpreguntas = $numSubpreguntas;
    
        return $this;
    }

    /**
     * Obtener numSubpreguntas
     *
     * @return integer 
     */
    public function getNumSubpreguntas()
    {
        return $this->numSubpreguntas;
    }

    /**
     * Establecer repetirPregunta
     *
     * @param integer 
     * @return Pregunta
     */
    public function setRepetirPregunta($repetirPregunta)
    {
        $this->repetirPregunta = $repetirPregunta;
    
        return $this;
    }

    /**
     * Obtener repetirPregunta
     *
     * @return integer 
     */
    public function getRepetirPregunta()
    {
        return $this->repetirPregunta;
    }

    /**
     * Establecer idParte
     *
     * @param \proyecto\backendBundle\Entity\ParteEstudio 
     * @return Pregunta
     */
    public function setIdParteEstudio(\proyecto\backendBundle\Entity\ParteEstudio $idParteEstudio = null)
    {
        $this->idParteEstudio = $idParteEstudio;
    
        return $this;
    }

    /**
     * Obtener idParte
     *
     * @return \proyecto\backendBundle\Entity\ParteEstudio 
     */
    public function getIdParteEstudio()
    {
        return $this->idParteEstudio;
    }
    
    /**
     * Establecer Subpreguntas
     *
     * @param ArrayCollection 
     * @return Pregunta
     */
    public function setSubpreguntas($subpreguntas = null)
    {
        $this->subpreguntas = $subpreguntas;
        foreach ($subpreguntas as $subpregunta) {
            $subpregunta->setIdPregunta($this);
        }
        return $this;
    }
    
    /**
     * Obtener Subpreguntas
     *
     * @return ArrayCollection 
     */
    public function getSubpreguntas()
    {
        return $this->subpreguntas;
    }
}