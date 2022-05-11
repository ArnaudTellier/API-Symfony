<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitCommande
 *
 * @ORM\Table(name="produit_commande", indexes={@ORM\Index(name="fkIdx_32", columns={"id_commande"}), @ORM\Index(name="fkIdx_182", columns={"id_declinaison_produit"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProduitCommandeRepository")
 */
class ProduitCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantité_produit", type="integer", nullable=false)
     */
    private $quantitéProduit;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_HT", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixHt;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_TVA", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantTva;

    /**
     * @var \Commande
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $idCommande;

    /**
     * @var \DeclinaisonProduit
     *
     * @ORM\ManyToOne(targetEntity="DeclinaisonProduit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_declinaison_produit", referencedColumnName="id_declinaison_produit")
     * })
     */
    private $idDeclinaisonProduit;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getQuantitéProduit(): ?int
    {
        return $this->quantitéProduit;
    }

    public function setQuantitéProduit(int $quantitéProduit): self
    {
        $this->quantitéProduit = $quantitéProduit;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prixHt;
    }

    public function setPrixHt(float $prixHt): self
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    public function getMontantTva(): ?float
    {
        return $this->montantTva;
    }

    public function setMontantTva(float $montantTva): self
    {
        $this->montantTva = $montantTva;

        return $this;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getIdDeclinaisonProduit(): ?DeclinaisonProduit
    {
        return $this->idDeclinaisonProduit;
    }

    public function setIdDeclinaisonProduit(?DeclinaisonProduit $idDeclinaisonProduit): self
    {
        $this->idDeclinaisonProduit = $idDeclinaisonProduit;

        return $this;
    }


}
