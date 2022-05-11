<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Declinaison
 *
 * @ORM\Table(name="declinaison")
 * @ORM\Entity(repositoryClass="App\Repository\DeclinaisonRepository")
 */
class Declinaison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_declinaison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDeclinaison;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_declinaison", type="text", length=65535, nullable=false)
     */
    private $libelleDeclinaison;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_declinaison", type="text", length=65535, nullable=true, options={"default"="NULL"})
     */
    private $descriptionDeclinaison = 'NULL';

    public function getIdDeclinaison(): ?int
    {
        return $this->idDeclinaison;
    }

    public function getLibelleDeclinaison(): ?string
    {
        return $this->libelleDeclinaison;
    }

    public function setLibelleDeclinaison(string $libelleDeclinaison): self
    {
        $this->libelleDeclinaison = $libelleDeclinaison;

        return $this;
    }

    public function getDescriptionDeclinaison(): ?string
    {
        return $this->descriptionDeclinaison;
    }

    public function setDescriptionDeclinaison(?string $descriptionDeclinaison): self
    {
        $this->descriptionDeclinaison = $descriptionDeclinaison;

        return $this;
    }


}
