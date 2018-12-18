<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Receta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text")
     */
    private $ingredientes;

    /**
     * @ORM\Column(type="text")
     */
    private $preparacion;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $dificultad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="recetas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="recetas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentario", mappedBy="receta_id")
     */
    private $comentarios;

    /**
     * @ORM\Column(type="string", length=250, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Foto", mappedBy="receta_id", orphanRemoval=true)
     */
    private $fotos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ruta;


    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->fotos = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getIngredientes(): ?string
    {
        return $this->ingredientes;
    }

    public function setIngredientes(string $ingredientes): self
    {
        $this->ingredientes = $ingredientes;

        return $this;
    }

    public function getPreparacion(): ?string
    {
        return $this->preparacion;
    }

    public function setPreparacion(string $preparacion): self
    {
        $this->preparacion = $preparacion;

        return $this;
    }

    public function getDificultad(): ?string
    {
        return $this->dificultad;
    }

    public function setDificultad(string $dificultad): self
    {
        $this->dificultad = $dificultad;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

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
            $comentario->setRecetaId($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getRecetaId() === $this) {
                $comentario->setRecetaId(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Foto[]
     */
    public function getFotos(): Collection
    {
        return $this->fotos;
    }

    public function addFoto(Foto $foto): self
    {
        if (!$this->fotos->contains($foto)) {
            $this->fotos[] = $foto;
            $foto->setRecetaId($this);
        }

        return $this;
    }

    public function removeFoto(Foto $foto): self
    {
        if ($this->fotos->contains($foto)) {
            $this->fotos->removeElement($foto);
            // set the owning side to null (unless already changed)
            if ($foto->getRecetaId() === $this) {
                $foto->setRecetaId(null);
            }
        }

        return $this;
    }

    //Obtenemo la ruta
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(string $ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }
}
