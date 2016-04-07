<?php

namespace proyecto\backendBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Entidad Informe
 * @author Javier Burguillo Sánchez
 */
class Informe
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \proyecto\backendBundle\Entity\Estudio
     */
    private $idParteEstudio;
    
    /**
     * @var string
     */
    private $tituloGrafico;

    /**
     * @var arrayCollection
     */
    private $columnas;
    
    
    public function __construct()
    {
        $this->columnas = new ArrayCollection();
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
     * Establecer idParteEstudio
     *
     * @param \proyecto\backendBundle\Entity\Estudio 
     * @return Informe
     */
    public function setIdParteEstudio(\proyecto\backendBundle\Entity\ParteEstudio $idParteEstudio = null)
    {
        $this->idParteEstudio = $idParteEstudio;
    
        return $this;
    }

    /**
     * Obtener idParteEstudio
     *
     * @return \proyecto\backendBundle\Entity\ParteEstudio 
     */
    public function getIdParteEstudio()
    {
        return $this->idParteEstudio;
    }
    


    /**
     * Establecer tituloGrafico
     *
     * @param string 
     * @return Informe
     */
    public function setTituloGrafico($tituloGrafico)
    {
        $this->tituloGrafico = $tituloGrafico;

        return $this;
    }

    /**
     * Obtener tituloGrafico
     *
     * @return string 
     */
    public function getTituloGrafico()
    {
        return $this->tituloGrafico;
    }
    
    /**
     * Establecer Columnas
     *
     * @param ArrayCollection 
     * @return Informe
     */
    public function setColumnas($columnas = null)
    {
        $this->columnas = $columnas;
        foreach ($columnas as $columna) {
            $columna->setIdInforme($this);
        }
        return $this;
    }
    
    /**
     * Obtener Columnas
     *
     * @return ArrayCollection 
     */
    public function getColumnas()
    {
        return $this->columnas;
    }
    
    /**
     * Método mágico. Devuelve el campo irdentificador principal para que no sea el id
     *
     * @return string 
     */
    public function __toString ()
    {
        return $this->tituloGrafico;
    }
}
