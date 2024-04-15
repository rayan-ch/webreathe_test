<?php

namespace App\Entity;

use App\Repository\TypeModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeModuleRepository::class)]
class TypeModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $grandeurPhysique = null;

    #[ORM\Column(length: 10)]
    private ?string $unite = null;

    #[ORM\OneToMany(targetEntity: Modules::class, mappedBy: 'typeModule')]
    private Collection $Modules;

    public function __construct()
    {
        $this->Modules = new ArrayCollection();
    }

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

    public function getGrandeurPhysique(): ?string
    {
        return $this->grandeurPhysique;
    }

    public function setGrandeurPhysique(string $grandeurPhysique): static
    {
        $this->grandeurPhysique = $grandeurPhysique;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection<int, Modules>
     */
    public function getModules(): Collection
    {
        return $this->Modules;
    }

    public function addModule(Modules $module): static
    {
        if (!$this->Modules->contains($module)) {
            $this->Modules->add($module);
            $module->setTypeModule($this);
        }

        return $this;
    }

    public function removeModule(Modules $module): static
    {
        if ($this->Modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getTypeModule() === $this) {
                $module->setTypeModule(null);
            }
        }

        return $this;
    }
}
