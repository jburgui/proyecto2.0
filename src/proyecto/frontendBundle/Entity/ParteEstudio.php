<?php

namespace proyecto\frontendBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Entidad ParteEstudio
 * @author Javier Burguillo Sánchez
 */
class ParteEstudio
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
     * @var integer
     */
    private $numOrden;

    /**
     * @var integer
     */
    private $numPreguntas;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var boolean
     */
    private $guardarTiempoRespuesta = false;

    /**
     * @var integer
     */
    private $numPreguntasDePrueba = 0;

    /**
     * @var \proyecto\frontendBundle\Entity\Estudio
     */
    private $idEstudio;

    /**
     * @var \proyecto\frontendBundle\Entity\TipoPregunta
     */
    private $idTipoPregunta;

    /**
     * @var ArrayCollection \proyecto\frontendBundle\Entity\pantallaBienvenida
     */
    private $pantallasBienvenida;
    /**
     * @var ArrayCollection \proyecto\frontendBundle\Entity\pregunta
     */
    private $preguntas;
    

    public function __construct()
    {
        $this->pantallasBienvenida = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
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
     * Establecer nombre
     *
     * @param string 
     * @return ParteEstudio
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
     * Establecer numOrden
     *
     * @param integer 
     * @return ParteEstudio
     */
    public function setNumOrden($numOrden)
    {
        $this->numOrden = $numOrden;
    
        return $this;
    }

    /**
     * Obtener numOrden
     *
     * @return integer 
     */
    public function getNumOrden()
    {
        return $this->numOrden;
    }

    /**
     * Establecer numPreguntas
     *
     * @param integer 
     * @return ParteEstudio
     */
    public function setNumPreguntas($numPreguntas)
    {
        $this->numPreguntas = $numPreguntas;
    
        return $this;
    }

    /**
     * Obtener numPreguntas
     *
     * @return integer 
     */
    public function getNumPreguntas()
    {
        return $this->numPreguntas;
    }

    /**
     * Establecer titulo
     *
     * @param string 
     * @return ParteEstudio
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Obtener titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Establecer guardarTiempoRespuesta
     *
     * @param boolean 
     * @return ParteEstudio
     */
    public function setGuardarTiempoRespuesta($guardarTiempoRespuesta)
    {
        $this->guardarTiempoRespuesta = $guardarTiempoRespuesta;
    
        return $this;
    }

    /**
     * Obtener guardarTiempoRespuesta
     *
     * @return boolean 
     */
    public function getGuardarTiempoRespuesta()
    {
        return $this->guardarTiempoRespuesta;
    }

    

    /**
     * Establecer numPreguntasDePrueba
     *
     * @param integer 
     * @return ParteEstudio
     */
    public function setNumPreguntasDePrueba($numPreguntasDePrueba)
    {
        $this->numPreguntasDePrueba = $numPreguntasDePrueba;
    
        return $this;
    }

    /**
     * Obtener numPreguntasDePrueba
     *
     * @return integer 
     */
    public function getNumPreguntasDePrueba()
    {
        return $this->numPreguntasDePrueba;
    }

    /**
     * Establecer idEstudio
     *
     * @param \proyecto\frontendBundle\Entity\Estudio 
     * @return ParteEstudio
     */
    public function setIdEstudio(\proyecto\frontendBundle\Entity\Estudio $idEstudio = null)
    {
        $this->idEstudio = $idEstudio;
    
        return $this;
    }

    /**
     * Obtener idEstudio
     *
     * @return \proyecto\frontendBundle\Entity\Estudio 
     */
    public function getIdEstudio()
    {
        return $this->idEstudio;
    }

    /**
     * Establecer idTipoPregunta
     *
     * @param \proyecto\frontendBundle\Entity\TipoPregunta 
     * @return ParteEstudio
     */
    public function setIdTipoPregunta(\proyecto\frontendBundle\Entity\TipoPregunta $idTipoPregunta = null)
    {
        $this->idTipoPregunta = $idTipoPregunta;
    
        return $this;
    }

    /**
     * Obtener idTipoPregunta
     *
     * @return \proyecto\frontendBundle\Entity\TipoPregunta 
     */
    public function getIdTipoPregunta()
    {
        return $this->idTipoPregunta;
    }
    
    /**
     * Establecer PantallasBienvenida
     *
     * @param ArrayCollection
     * @return ParteEstudio
     */
    public function setPantallasBienvenida($pantallasBienvenida = null)
    {
        $this->pantallasBienvenida = $pantallasBienvenida;
        foreach ($pantallasBienvenida as $pantallaBienvenida) {
            $pantallaBienvenida->setIdParteEstudio($this);
        }
        return $this;
    }
    
    /**
     * Obtener PantallasBienvenida
     *
     * @return ArrayCollection
     */
    public function getPantallasBienvenida()
    {
        return $this->pantallasBienvenida;
    }
    
    /**
     * Establecer Preguntas
     *
     * @param ArrayCollection
     * @return ParteEstudio
     */
    public function setPreguntas( $preguntas = null)
    {
        $this->preguntas = $preguntas;
        foreach ($preguntas as $pregunta) {
            $pregunta->setIdParteEstudio($this);
        }
        return $this;
    }
    
    /**
     * Obtener Preguntas
     *
     * @return ArrayCollection 
     */
    public function getPreguntas()
    {
        return $this->preguntas;
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