<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="apodo", message="Username already taken")
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $Apellidos;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $email;
    
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword; 

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $pass;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $apodo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Receta", mappedBy="usuario")
     */
    private $recetas;

    /**
     * @return string
     */

     public function __toString()
    {
        return strval($this->apodo);
    }
    
    public function __construct()
    {
        $this->recetas = new ArrayCollection();
        //$this->roles = array('ROLE_USER');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): self
    {
        $this->Apellidos = $Apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        $this->password = null;
    }
    
    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

        return $this;
    }

    public function getApodo(): ?string
    {
        return $this->apodo;
    }

    public function setApodo(?string $apodo): self
    {
        $this->apodo = $apodo;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
  
    /**
     * @return Collection|Receta[]
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    public function addReceta(Receta $receta): self
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas[] = $receta;
            $receta->setUsuario($this);
        }

        return $this;
    }

    public function removeReceta(Receta $receta): self
    {
        if ($this->recetas->contains($receta)) {
            $this->recetas->removeElement($receta);
            // set the owning side to null (unless already changed)
            if ($receta->getUsuario() === $this) {
                $receta->setUsuario(null);
            }
        }

        return $this;
    }
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedValue() {
        
        $this->created = new \DateTime();
    }
    
    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }
    public function getUsername(){
        
        return $this->apodo;
    }
    
    public function getPassword(){
        
        return $this->pass;
    }
    
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->apodo,
            $this->email,
            $this->pass
        ]);
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($string)
    {
        list(
            $this->id,
            $this->apodo,
            $this->email,
            $this->pass                
            ) = unserialize($string, ['allowed_classes' => false]);
    }
}
