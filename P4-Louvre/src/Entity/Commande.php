<?php

namespace App\Entity;

use App\Validator\Constraints\ControlDateVisite;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Email(
     *     message = "L''email '{{ value }}' n''est pas valide.",
     *     checkMX = true
     * )
     */	 
    private $mail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCommande;

    /**
     * @ORM\Column(type="date")
	 * @ControlDateVisite()
     */
    private $dateVisite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="commande", orphanRemoval=true, cascade={"persist"})
     */
    private $tickets;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $payer;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
		$this->payer = false;
    }
	
    public function getCodeCommande() 
    {
		$prefix = $this->dateCommande->format('WMyz');
		$charAscii = rand(97,122);
		
        return $prefix . \chr($charAscii) . $this->id;
    }
	
    public function getJourSemaineCommande() 
    {
		return $this->dateVisite->format('w');
    }
	
    public function getJourCommande() 
    {
		return $this->dateVisite->format('d');
    }

    public function getMoisCommande() 
    {
		return $this->dateVisite->format('m');
    }		
	
    public function getId()
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->dateVisite;
    }

    public function setDateVisite(\DateTimeInterface $dateVisite): self
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCommande($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getCommande() === $this) {
                $ticket->setCommande(null);
            }
        }

        return $this;
    }

    public function getPayer(): ?bool
    {
        return $this->payer;
    }

    public function setPayer(?bool $payer): self
    {
        $this->payer = $payer;

        return $this;
    }
}
