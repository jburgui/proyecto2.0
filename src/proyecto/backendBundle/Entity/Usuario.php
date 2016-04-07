<?php

namespace proyecto\backendBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Entidad Usuario
 * @author Javier Burguillo Sánchez
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $nombre;

    /**
     * @var string
     */
    protected $contrasena;

    /**
     * @var \proyecto\frontendBundle\Entity\Rol
     */
    protected  $rol;

    /**
     * @var string
     */
    protected $email;
    
    /**
     * Obtener id
     *
     * @return integer 
     */
    
    
    public function getid()
    {
        return $this->id;
    }
    /**
     * Establecer nombre
     *
     * @param string 
     * @return Usuario
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
     * Igual que getNombre pero personalizada para Symfony
     */
    public function getUsername()
    {
        return $this->getNombre();
    }
    /**
     * Establecer contrasena
     *
     * @param string 
     * @return Usuario
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    
        return $this;
    }
    /**
     * Igual que setContrasena pero personalizada para Symfony
     */
    public function setPassword($contrasena)
    {
        $this->contrasena = $contrasena;
    
        return $this;
    }
    
    /**
     * Igual que getContrasena pero personalizada para Symfony
     */
    public function getPassword()
    {
        return $this->getcontrasena();
    }
    
    /**
     * Obtener contrasena
     *
     * @return string 
     */
    public function getcontrasena()
    {
        return $this->contrasena;
    }
    /**
     * Establecer rol
     *
     * @param \proyecto\frontendBundle\Entity\Rol 
     * @return Usuario
     */
    public function setRol(\proyecto\backendBundle\Entity\Rol $rol = null)
    {
        $this->rol = $rol;
    
        return $this;
    }

    /**
     * Obtener nombre del rol para Symfony
     *
     * @return \proyecto\frontendBundle\Entity\Rol 
     */
    public function getRoles()
    {
        $aux = array($this->rol->getNombre());
        return $aux;
    }
    
    /**
     * Obtener rol
     *
     * @return \proyecto\frontendBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }
    /**
     * Erases the user credentials.(función necesaria para Symfony)
     */
    public function eraseCredentials() {

    }
    
    /**
     * Obtener salt(función necesaria para Symfony)
     *
     * @return string
     */
    public function getSalt()
    {
        return '';
    }
    /**
     * serializa un Usuario(función necesaria para Symfony)
     */
    public function serialize()
    {
        return \json_encode(array(
            $this->nombre, $this->contrasena,
                        $this->rol, $this->id
            ));
    }

    /**
     * Desereializa un Usuario (función necesaria para Symfony)
     * @param serialized
     */
    public function unserialize($serialized)
    {
        list(
                $this->nombre, $this->contrasena,
                $this->rol, $this->id
                ) = \json_decode($serialized);
    }
    /**
     * Método mágico.Se ejecuta antes de serializar.(función necesaria para Symfony)
     */
    public function __sleep()
    { 
        return array('id');
    }

    /**
     * Establecer email
     *
     * @param string 
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Obtener email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}