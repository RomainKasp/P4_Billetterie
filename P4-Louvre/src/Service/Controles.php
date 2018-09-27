<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;

class Controles
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
	
    public function ctlPlaces(ContainerInterface $container, $dateVisite, $nbrTickets)
    {
		$maxPlaces = $container->getParameter('places.journ');	
		$conn = $this->em->getConnection();

		$sql = 'SELECT COUNT(t.id) AS compteur
				FROM ticket t
				INNER JOIN commande c
				on c.id = t.commande_id
				WHERE c.date_visite = :dateVisite
				';
		$stmt = $conn->prepare($sql);
		$stmt->execute(['dateVisite' => $dateVisite]);
		$data=$stmt->fetchAll();
		
		$test = $data[0]["compteur"] + $nbrTickets;

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
			$control = $this->ctlPlaces($container, $commande->getDateVisite()->format('Y-m-d'), $nbrTickets);
		
		if ($control == 0)
			// Controle si la date est correcte
			$control = $this->ctlDate($commande, $container->getParameter('jourFerier'));	
		
		//Retourne un message d'erreur si besoin
		return $control;
	}
	
    public function ctlDate(Commande $commande ,$jourFerie)
    {
		//print_r($commande); die;
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
/*
    public function gestionErreur($numErreur, $messages)
    {
		if ($numErreur == 0)
			return [true, " "];
		else
			return [false, $messages[$numErreur] ];
	}	*/
}