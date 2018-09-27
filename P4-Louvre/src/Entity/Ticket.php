<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demiJournee;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $nationalite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tarifReduit;

    public function getId()
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDemiJournee(): ?bool
    {
        return $this->demiJournee;
    }

    public function setDemiJournee(bool $demiJournee): self
    {
        $this->demiJournee = $demiJournee;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getTarifReduit(): ?bool
    {
        return $this->tarifReduit;
    }

    public function setTarifReduit(bool $tarifReduit): self
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    public function getAgeVisiteur()
    {
		$time = strtotime($this->dateNaissance->format('Y-m-d H:i:s'));
		if($time === false){ return 30; 	}
	
		$year_diff = '';
		$date = date('Y-m-d', $time);
		list($year,$month,$day) = explode('-',$date);
		$year_diff = date('Y') - $year;
		$month_diff = date('m') - $month;
		$day_diff = date('d') - $day;
		if ($day_diff < 0 || $month_diff < 0) $year_diff--;
	
		return $year_diff;
    }	
}
