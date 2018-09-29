<?php

namespace App\Controller;

use App\Service\GestionCalculs;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Etape3Controller extends Controller
{
    private $session;
    private $gestionCalcul;
    private $parameterBag;
	
    public function __construct(GestionCalculs $gestionCalcul, SessionInterface $session, ParameterBagInterface $parameterBag)
    {
        $this->session = $session;
        $this->gestionCalcul = $gestionCalcul;
        $this->parameterBag = $parameterBag;
    }
	
    /**
     * @Route("/paiement", name="step3")
     */
    public function index()
    {
		$commande = $this->session->get('commande');
		
		if (is_null($commande)) {
		    return $this->redirectToRoute('step1');
        }
		
		$tickets = $commande->getTickets();
		
		$traitement= $this->gestionCalcul->trtTickets($this->parameterBag, $tickets);
		
		$this->session->set('commande', $commande);
		$this->session->set('amount', $traitement["total"]);
		$this->session->set('tabRecap', $traitement["recap"]);
		
        return $this->render('etape3/index.html.twig', [
			'tabRecap' => $traitement["recap"],
			'total' => $traitement["total"],
			'mailCommande' => $commande->getMail(),
        ]);
    }
}
