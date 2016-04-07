<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad Fragmento
 * @author Javier Burguillo Sánchez
 */
class Fragmento
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var boolean
     */
    private $primeraLetra;

    /**
     * @var boolean
     */
    private $ultimaLetra;

    /**
     * @var boolean
     */
    private $dosEspaciosJuntos;

    /**
     * @var boolean
     */
    private $tresLetrasJuntas;

    /**
     * @var integer
     */
    private $ratioDadasEliminadas;

    /**
     * @var integer
     */
    private $letrasDadas;

    /**
     * @var integer
     */
    private $ratioVocalesConsonantes;

    /**
     * @var \proyecto\frontendBundle\Entity\Adjetivo
     */
    private $idAdjetivo;


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
     * Establecer nombre
     *
     * @param string 
     * @return Fragmento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Obtener nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Establecer primeraLetra
     *
     * @param boolean 
     * @return Fragmento
     */
    public function setPrimeraLetra($primeraLetra)
    {
        $this->primeraLetra = $primeraLetra;
    
        return $this;
    }

    /**
     * Obtener primeraLetra
     *
     * @return boolean 
     */
    public function getPrimeraLetra()
    {
        return $this->primeraLetra;
    }

    /**
     * Establecer ultimaLetra
     *
     * @param boolean 
     * @return Fragmento
     */
    public function setUltimaLetra($ultimaLetra)
    {
        $this->ultimaLetra = $ultimaLetra;
    
        return $this;
    }

    /**
     * Obtener ultimaLetra
     *
     * @return boolean 
     */
    public function getUltimaLetra()
    {
        return $this->ultimaLetra;
    }

    /**
     * Establecer dosEspaciosJuntos
     *
     * @param boolean 
     * @return Fragmento
     */
    public function setDosEspaciosJuntos($dosEspaciosJuntos)
    {
        $this->dosEspaciosJuntos = $dosEspaciosJuntos;
    
        return $this;
    }

    /**
     * Obtener dosEspaciosJuntos
     *
     * @return boolean 
     */
    public function getDosEspaciosJuntos()
    {
        return $this->dosEspaciosJuntos;
    }

    /**
     * Establecer tresLetrasJuntas
     *
     * @param boolean 
     * @return Fragmento
     */
    public function setTresLetrasJuntas($tresLetrasJuntas)
    {
        $this->tresLetrasJuntas = $tresLetrasJuntas;
    
        return $this;
    }

    /**
     * Obtener tresLetrasJuntas
     *
     * @return boolean 
     */
    public function getTresLetrasJuntas()
    {
        return $this->tresLetrasJuntas;
    }

    /**
     * Establecer ratioDadasEliminadas
     *
     * @param integer 
     * @return Fragmento
     */
    public function setRatioDadasEliminadas($ratioDadasEliminadas)
    {
        $this->ratioDadasEliminadas = $ratioDadasEliminadas;
    
        return $this;
    }

    /**
     * Obtener ratioDadasEliminadas
     *
     * @return integer 
     */
    public function getRatioDadasEliminadas()
    {
        return $this->ratioDadasEliminadas;
    }

    /**
     * Establecer letrasDadas
     *
     * @param integer 
     * @return Fragmento
     */
    public function setLetrasDadas($letrasDadas)
    {
        $this->letrasDadas = $letrasDadas;
    
        return $this;
    }

    /**
     * Obtener letrasDadas
     *
     * @return integer 
     */
    public function getLetrasDadas()
    {
        return $this->letrasDadas;
    }

    /**
     * Establecer ratioVocalesConsonantes
     *
     * @param integer 
     * @return Fragmento
     */
    public function setRatioVocalesConsonantes($ratioVocalesConsonantes)
    {
        $this->ratioVocalesConsonantes = $ratioVocalesConsonantes;
    
        return $this;
    }

    /**
     * Obtener ratioVocalesConsonantes
     *
     * @return integer 
     */
    public function getRatioVocalesConsonantes()
    {
        return $this->ratioVocalesConsonantes;
    }

    /**
     * Establecer idAdjetivo
     *
     * @param \proyecto\frontendBundle\Entity\Adjetivo 
     * @return Fragmento
     */
    public function setIdAdjetivo(\proyecto\frontendBundle\Entity\Adjetivo $idAdjetivo = null)
    {
        $this->idAdjetivo = $idAdjetivo;
    
        return $this;
    }

    /**
     * Obtener idAdjetivo
     *
     * @return \proyecto\frontendBundle\Entity\Adjetivo 
     */
    public function getIdAdjetivo()
    {
        return $this->idAdjetivo;
    }
    
    /**
     * Método mágico. Devuelve el campo irdentificador principal para que no sea el id
     *
     * @return string 
     */
    public function __toString (){
        return $this->nombre;
    }
}