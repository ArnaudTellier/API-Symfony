<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatutCommande
 *
 * @ORM\Table(name="statut_commande")
 * @ORM\Entity(repositoryClass="App\Repository\StatutCommandeRepository")
 */
class StatutCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_statut", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_statut", type="string", length=45, nullable=false)
     */
    private $libelleStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="description_statut", type="text", length=65535, nullable=false)
     */
    private $descriptionStatut;

    public function getIdStatut(): ?int
    {
        return $this->idStatut;
    }

    public function getLibelleStatut(): ?string
    {
        return $this->libelleStatut;
    }

    public function setLibelleStatut(string $libelleStatut): self
    {
        $this->libelleStatut = $libelleStatut;

        return $this;
    }

    public function getDescriptionStatut(): ?string
    {
        return $this->descriptionStatut;
    }

    public function setDescriptionStatut(string $descriptionStatut): self
    {
        $this->descriptionStatut = $descriptionStatut;

        return $this;
    }


}
