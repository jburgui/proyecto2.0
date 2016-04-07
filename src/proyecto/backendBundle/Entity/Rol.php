<?php

namespace proyecto\backendBundle\Entity;
use Symfony\Component\Security\Core\Role\RoleInterface;


/**
 * Entidad Rol
 * @author Javier Burguillo Sánchez
 */
class Rol implements RoleInterface
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
     * @return Rol
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
     * Obtener rol. Función específica para Symfony
     *
     * @return string 
     */
    public function getRole() 
    {
        return $this->nombre;
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