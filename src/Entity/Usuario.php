<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="apodo", message="Username already taken")
 */
class Usuario implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(type="string", length=250, unique=true)
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
     * @ORM\Column(type="string", length=20)
     */
    private $apodo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * 
     */
    private $recetas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentario", mappedBy="usuario_id", orphanRemoval=true)
     */
    private $comentarios;

       
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
        $this->comentarios = new ArrayCollection();
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
    
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;

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
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->apodo,
            $this->email,
            $this->pass,
            $this->isActive
        ]);
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($string)
    {
        list(
            $this->id,
            $this->apodo,
            $this->email,
            $this->pass,
            $this->isActive
            ) = unserialize($string, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Comentario[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setUsuarioId($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getUsuarioId() === $this) {
                $comentario->setUsuarioId(null);
            }
        }

        return $this;
    }

    

}
