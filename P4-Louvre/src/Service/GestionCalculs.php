<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Ticket;

class GestionCalculs
{
	private $trans;
	private $prixTotal;
	private $tableTarif;
	private $tableTarifReduit;
	
	public function __construct(TranslatorInterface $trans){
		$this->trans = $trans;
	}
	
    public function trtTickets(ParameterBagInterface $parameterBag, $tickets)
    {
		$recap = null;
		$this->prixTotal = 0;
		$this->tableTarif 		= $parameterBag->get('tarifs');
		$this->tableTarifReduit = $parameterBag->get('tarifReduit');
		
		foreach ($tickets AS $tick){
			$trait = $this->definirTarif($tick);
			if ($tick->getDemiJournee()){
				$trait["Prix"] /= 2;
				$trait["Nom"] .= " (".$this->trans->trans('calc.demijour').")";
			}
			
			$this->prixTotal += $trait["Prix"];
			$recap[] = [	"Nom" => $tick->getNom(), 
							"Prenom" => $tick->getPrenom() , 
							"Tarif" => $trait["Nom"], 
							"Prix"  => $trait["Prix"], 
							];	
		}
		
		return ["total" => $this->prixTotal,
				"recap" => $this->creerTabRecap($recap)
				];
    }
	
    private function definirTarif(Ticket $ticket)
    {
        if ($ticket->getTarifReduit())
			return $this->tableTarifReduit;
		else
			return $this->parcoursTarifs($ticket->getAgeVisiteur());
    }
	
    private function parcoursTarifs($age)
    {
        foreach ($this->tableTarif AS $tarif){
					
			if ((int) $tarif["AgeMax"] < $age){
				$resultat =[	"Nom" => $tarif["Nom"], 
								"Prix" => floatval($tarif["Prix"])   ];
				return $resultat;
			}
		}
    }	
	
    private function creerTabRecap($recap)
    {
		$tabRecap = "<table width='70%'>";
		$transColNom = $this->trans->trans('calc.nom');
		$transColTar = $this->trans->trans('calc.tarif');
		$transColPri = $this->trans->trans('calc.prix');
		$tabRecap .= "<tr><th style='text-align:center'>".$transColNom."</th><th style='text-align:center'>".$transColTar."</th><th style='text-align:center'>".$transColPri." (€)</th></tr>";
		
		foreach ($recap AS $ligne){		
			$tabRecap .= "<tr>";
			$tabRecap .= "<td style='text-align:center'>". $ligne["Nom"] ." ". $ligne["Prenom"] ."</td>";
			$tabRecap .= "<td style='text-align:center'>". $this->trans->trans($ligne["Tarif"]) ."</td>";
			$tabRecap .= "<td style='text-align:center'>". $ligne["Prix"] ."</td>";
			$tabRecap .= "</tr>";
		}

		$tabRecap .= "<th colspan='2' style='text-align:right'>Total :</th><th>". $this->prixTotal ."</th>";	
		$tabRecap .= "</table>";
		
		return $tabRecap;
    }	
}