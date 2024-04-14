<?php

namespace App\Entity;

use App\Repository\ModulesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ModulesRepository::class)]
class Modules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $fabricant = null;

    #[ORM\Column]
    private ?int $numeroSerie = null;

    #[ORM\Column(length: 20)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $donnees = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeModule $typeModule = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getFabricant(): ?string
    {
        return $this->fabricant;
    }

    public function setFabricant(string $fabricant): static
    {
        $this->fabricant = $fabricant;

        return $this;
    }

    public function getNumeroSerie(): ?int
    {
        return $this->numeroSerie;
    }

    public function setNumeroSerie(int $numeroSerie): static
    {
        $this->numeroSerie = $numeroSerie;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDonnees(): ?array
    {
        return $this->donnees;
    }

    public function setDonnees(?array $donnees): static
    {
        $this->donnees = $donnees;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getTypeModule(): ?TypeModule
    {
        return $this->typeModule;
    }

    public function setTypeModule(?TypeModule $typeModule): static
    {
        $this->typeModule = $typeModule;

        return $this;
    }

}
