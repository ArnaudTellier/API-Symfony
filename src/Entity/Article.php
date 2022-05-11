<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="fkIdx_139", columns={"id_rubrique"}), @ORM\Index(name="fkIdx_136", columns={"id_employe"})})
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")

 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="text", length=65535, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=true)
     */
    private $contenu;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_création_article", type="date", nullable=true)
     */

    //private $dateCréationArticle;

    /**
     * @var \Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_employe", referencedColumnName="id_employe")
     * })
     */
    private $idEmploye;

    /**
     * @var \Rubrique
     *
     * @ORM\ManyToOne(targetEntity="Rubrique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_rubrique", referencedColumnName="id_rubrique")
     * })
     */
    private $idRubrique;

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }
/*
    public function getDateCréationArticle(): ?\DateTimeInterface
    {
        return $this->dateCréationArticle;
    }

    public function setDateCréationArticle(\DateTimeInterface $dateCréationArticle): self
    {
        $this->dateCréationArticle = $dateCréationArticle;

        return $this;
    }
*/
    public function getIdEmploye(): ?Employe
    {
        return $this->idEmploye;
    }

    public function setIdEmploye(?Employe $idEmploye): self
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }

    public function getIdRubrique(): ?Rubrique
    {
        return $this->idRubrique;
    }

    public function setIdRubrique(?Rubrique $idRubrique): self
    {
        $this->idRubrique = $idRubrique;

        return $this;
    }


}
