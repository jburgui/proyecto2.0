<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad RangoRespuesta
 * @author Javier Burguillo Sánchez
 */
class RangoRespuesta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idSubpregunta;

    /**
     * @var integer
     */
    private $rangoMax;

    /**
     * @var integer
     */
    private $rangoMin;


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
     * Establecer idSubpregunta
     *
     * @param integer 
     * @return RangoRespuesta
     */
    public function setIdSubpregunta($idSubpregunta)
    {
        $this->idSubpregunta = $idSubpregunta;
    
        return $this;
    }

    /**
     * Obtener idSubpregunta
     *
     * @return integer 
     */
    public function getIdSubpregunta()
    {
        return $this->idSubpregunta;
    }

    /**
     * Establecer rangoMax
     *
     * @param integer 
     * @return RangoRespuesta
     */
    public function setRangoMax($rangoMax)
    {
        $this->rangoMax = $rangoMax;
    
        return $this;
    }

    /**
     * Obtener rangoMax
     *
     * @return integer 
     */
    public function getRangoMax()
    {
        return $this->rangoMax;
    }

    /**
     * Establecer rangoMin
     *
     * @param integer 
     * @return RangoRespuesta
     */
    public function setRangoMin($rangoMin)
    {
        $this->rangoMin = $rangoMin;
    
        return $this;
    }

    /**
     * Obtener rangoMin
     *
     * @return integer 
     */
    public function getRangoMin()
    {
        return $this->rangoMin;
    }
}