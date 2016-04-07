<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad OpcionRespuesta
 * @author Javier Burguillo Sánchez
 */
class OpcionRespuesta
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var \proyecto\frontendBundle\Entity\Subpregunta
     */
    private $idSubpregunta;


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
     * Establecer valor
     *
     * @param string 
     * @return OpcionRespuesta
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Obtener valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Establecer idSubpregunta
     *
     * @param \proyecto\frontendBundle\Entity\Subpregunta 
     * @return OpcionRespuesta
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
}