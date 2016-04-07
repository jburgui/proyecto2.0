<?php

namespace proyecto\backendBundle\Entity;


/**
 * Entidad Columna
 * @author Javier Burguillo Sánchez
 */
class Columna
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombreColumna;

    /**
     * @var string
     */
    private $tratamiento;

    /**
     * @var \proyecto\backendBundle\Entity\Informe
     */
    private $idInforme;

    /**
     * @var \proyecto\backendBundle\Entity\ParteEstudio
     */
    private $idSubpregunta;

    /**
     * @var integer indica si sale en la gráfica o no.
     */
    private $grafica;
    
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
     * Establecer nombreColumna
     *
     * @param string 
     * @return Columna
     */
    public function setNombreColumna($nombreColumna)
    {
        $this->nombreColumna = $nombreColumna;

        return $this;
    }

    /**
     * Obtener nombreColumna
     *
     * @return string 
     */
    public function getNombreColumna()
    {
        return $this->nombreColumna;
    }

    /**
     * Establecer tratamiento
     *
     * @param string 
     * @return Columna
     */
    public function setTratamiento($tratamiento)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    /**
     * Obtener tratamiento
     *
     * @return string 
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }

    /**
     * Establecer idInforme
     *
     * @param \proyecto\backendBundle\Entity\Informe 
     * @return Columna
     */
    public function setIdInforme(\proyecto\backendBundle\Entity\Informe $idInforme = null)
    {
        $this->idInforme = $idInforme;

        return $this;
    }

    /**
     * Obtener idInforme
     *
     * @return \proyecto\backendBundle\Entity\Informe 
     */
    public function getIdInforme()
    {
        return $this->idInforme;
    }

    /**
     * Establecer idSubpregunta
     *
     * @param \proyecto\backendBundle\Entity\ParteEstudio 
     * @return Columna
     */
    public function setIdSubpregunta(\proyecto\backendBundle\Entity\Subpregunta $idSubpregunta = null)
    {
        $this->idSubpregunta = $idSubpregunta;

        return $this;
    }

    /**
     * Obtener idSubpregunta
     *
     * @return \proyecto\backendBundle\Entity\IdSubpregunta
     */
    public function getIdSubpregunta()
    {
        return $this->idSubpregunta;
    }
    
    /**
     * Establecer grafica
     *
     * @param integer 
     * @return columna
     */
    public function setGrafica($grafica)
    {
        $this->grafica = $grafica;
    
        return $this;
    }

    /**
     * Obtener numSecciones
     *
     * @return integer 
     */
    public function getGrafica()
    {
        return $this->grafica;
    }
    
    /**
     * Método mágico. Devuelve el campo irdentificador principal para que no sea el id
     *
     * @return string 
     */
    public function __toString ()
    {
        return $this->nombreColumna;
    }
}
