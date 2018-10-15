<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Repository\TicketRepository;

class Controles
{
    private $repoTick;
    private $parameterBag;

    public function __construct(TicketRepository $repoTick, ParameterBagInterface $parameterBag)
    {
        $this->repoTick = $repoTick;
        $this->parameterBag = $parameterBag;
    }
	
    public function ctlPlaces($dateVisite, $nbrTickets)
    {
		$maxPlaces = $this->parameterBag->get('places.journ');	
		$placesReserve = $this->repoTick->nombreTicketParDate($dateVisite); 
		
		$test = $placesReserve + $nbrTickets;

		if ($test>$maxPlaces) 	return 102;
		else 					return 0;
	}
	
    public function ctlForm(Commande $commande, $nbrTickets, ContainerInterface $container)
    {
		// Controle d'une commande non vide
		if ($nbrTickets < 1)
			$control = 101;
		else
			// Controle de l'espace disponible
			$control = $this->ctlPlaces($commande->getDateVisite()->format('Y-m-d'), $nbrTickets);
		
		if ($control == 0)
			// Controle si la date est correcte
			$control = $this->ctlDate($commande, $this->parameterBag->get('jourFerier'));	
		
		//Retourne un message d'erreur si besoin
		return $control;
	}
	
    public function ctlDate(Commande $commande ,$jourFerie)
    {
		// Date de commande non passée
		if ($commande->getDateVisite() < $commande->getDateCommande())
			return 103;

		// Mardi non disponible
		if ($commande->getJourSemaineCommande() == 2)
		   return 104;

        // Hors jours ferié	   
		$index= $commande->getMoisCommande() -1;
		$tabJours = $jourFerie[$index];

		foreach ($tabJours as $jour) {
			if ($commande->getJourCommande() == $jour)
				return 105;
		}			
		
		return 0;
	}	
}