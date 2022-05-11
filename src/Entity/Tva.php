<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tva
 *
 * @ORM\Table(name="tva")
 * @ORM\Entity(repositoryClass="App\Repository\TvaRepository")
 */
class Tva
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_tva", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTva;

    /**
     * @var string
     *
     * @ORM\Column(name="pourcentageTVA", type="decimal", precision=10, scale=1, nullable=false)
     */
    private $pourcentagetva;

    public function getIdTva(): ?int
    {
        return $this->idTva;
    }

    public function getPourcentagetva(): ?string
    {
        return $this->pourcentagetva;
    }

    public function setPourcentagetva(string $pourcentagetva): self
    {
        $this->pourcentagetva = $pourcentagetva;

        return $this;
    }


}
