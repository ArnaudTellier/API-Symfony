<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeclinaisonProduit
 *
 * @ORM\Table(name="declinaison_produit", indexes={@ORM\Index(name="fkIdx_156", columns={"id_declinaison"}), @ORM\Index(name="fkIdx_159", columns={"id_produit"})})
 * @ORM\Entity(repositoryClass="App\Repository\DeclinaisonProduitRepository")
 */
class DeclinaisonProduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_declinaison_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idDeclinaisonProduit;

    /**
     * @var \Produit
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     * })
     */
    private $idProduit;

    /**
     * @var \Declinaison
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Declinaison")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_declinaison", referencedColumnName="id_declinaison")
     * })
     */
    private $idDeclinaison;

    public function getIdDeclinaisonProduit(): ?int
    {
        return $this->idDeclinaisonProduit;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdDeclinaison(): ?Declinaison
    {
        return $this->idDeclinaison;
    }

    public function setIdDeclinaison(?Declinaison $idDeclinaison): self
    {
        $this->idDeclinaison = $idDeclinaison;

        return $this;
    }


}
