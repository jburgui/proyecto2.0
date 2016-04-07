<?php

namespace proyecto\frontendBundle\Entity;


/**
 * Entidad Adjetivo
 * @author Javier Burguillo Sánchez
 */
class Adjetivo
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
     * @var string
     */
    private $tipo;

    /**
     * @var integer
     */
    private $frecTeorica;

    /**
     * @var integer
     */
    private $numLetras;

    /**
     * @var integer
     */
    private $numSilabas;

    /**
     * @var string
     */
    private $categoria;

    /**
     * @var integer
     */
    private $frecUsoComoAdj;

    /**
     * @var integer
     */
    private $numSignificados;


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
     * @return Adjetivo
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
     * Establecer tipo
     *
     * @param string 
     * @return Adjetivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Obtener tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Establecer frecTeorica
     *
     * @param integer 
     * @return Adjetivo
     */
    public function setFrecTeorica($frecTeorica)
    {
        $this->frecTeorica = $frecTeorica;
    
        return $this;
    }

    /**
     * Obtener frecTeorica
     *
     * @return integer 
     */
    public function getFrecTeorica()
    {
        return $this->frecTeorica;
    }

    /**
     * Establecer numLetras
     *
     * @param integer 
     * @return Adjetivo
     */
    public function setNumLetras($numLetras)
    {
        $this->numLetras = $numLetras;
    
        return $this;
    }

    /**
     * Obtener numLetras
     *
     * @return integer 
     */
    public function getNumLetras()
    {
        return $this->numLetras;
    }

    /**
     * Establecer numSilabas
     *
     * @param integer 
     * @return Adjetivo
     */
    public function setNumSilabas($numSilabas)
    {
        $this->numSilabas = $numSilabas;
    
        return $this;
    }

    /**
     * Obtener numSilabas
     *
     * @return integer 
     */
    public function getNumSilabas()
    {
        return $this->numSilabas;
    }

    /**
     * Establecer categoria
     *
     * @param string 
     * @return Adjetivo
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Obtener categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Establecer frecUsoComoAdj
     *
     * @param integer 
     * @return Adjetivo
     */
    public function setFrecUsoComoAdj($frecUsoComoAdj)
    {
        $this->frecUsoComoAdj = $frecUsoComoAdj;
    
        return $this;
    }

    /**
     * Obtener frecUsoComoAdj
     *
     * @return integer 
     */
    public function getFrecUsoComoAdj()
    {
        return $this->frecUsoComoAdj;
    }

    /**
     * Establecer numSignificados
     *
     * @param integer 
     * @return Adjetivo
     */
    public function setNumSignificados($numSignificados)
    {
        $this->numSignificados = $numSignificados;
    
        return $this;
    }

    /**
     * Obtener numSignificados
     *
     * @return integer 
     */
    public function getNumSignificados()
    {
        return $this->numSignificados;
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