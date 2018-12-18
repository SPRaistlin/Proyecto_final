<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComentarioRepository")
 */
class Comentario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="comentarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Receta", inversedBy="comentarios")
     */
    private $receta;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getUsuarioId(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuarioId(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getRecetaId(): ?Receta
    {
        return $this->receta;
    }

    public function setRecetaId(?Receta $receta): self
    {
        $this->receta = $receta;

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
    * @ORM\PrePersist
    */
    public function setCreatedValue() {
        $this->created = new \DateTime();
    }
}
