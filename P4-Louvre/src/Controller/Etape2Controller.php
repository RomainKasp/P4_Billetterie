<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Commande;
use App\Entity\Ticket;
use App\Form\TicketsType;
use App\Handlers\Forms\Etape2FormHandler;

class Etape2Controller extends Controller
{
    private $session;
    private $etape2FormHandler;
	
    public function __construct(Etape2FormHandler $etape2FormHandler, SessionInterface $session)
    {
        $this->session = $session;
        $this->etape2FormHandler = $etape2FormHandler;
    }
	
    /**
     * @Route("/tickets", name="step2")
     */
    public function index(Request $request)
    {
		$nbrTicke= $this->session->get('nbrTicke');
		$commande = $this->session->get('commande');
		$dateHeure = new \DateTime();
		$demiJournee = false;
		
		if ($commande->getDateVisite() == $commande->getDateCommande()){
			if ($dateHeure->format("H") > 13)
				$demiJournee = True;
		}
		
		$form = $this->createForm(TicketsType::class, $commande)->handleRequest($request);	
		$resultHandler = $this->etape2FormHandler->handle($form);
		
		if ($resultHandler){
			return $this->redirectToRoute('step3');
		}	
		
        return $this->render('etape2/index.html.twig', [
			'form' => $form->createView(),
			'nb_tickets' 	=> $nbrTicke,
			'demiJournee' 	=> $demiJournee,
        ]);
    }
}
