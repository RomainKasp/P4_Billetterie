<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Service\RetourStripe;

class ValidationController extends Controller
{
    private $session;
	private $entityManager;
	private $retStripe;
	
    public function __construct(SessionInterface $session, RetourStripe $retStripe, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->retStripe = $retStripe;
    }
	
    /**
     * @Route("/validation", name="validatePaiement")
     */
    public function index(Request $request,  \Swift_Mailer $mailer)
    {
		$tabRecap 	= $this->session->get('tabRecap');
		$amount 	= $this->session->get('amount')*100;
		$commande 	= $this->session->get('commande');
		$id 			= $commande->getId();
		$commande 		= $this->entityManager->getRepository(Commande::class)->find($id);
		$mailCommande 	= $commande->getMail();
		$dateCommande	= $commande->getDateCommande();
		$idCommande 	= $commande->getCodeCommande();
		
		//Retour de stripe (contrôle du paiement)
		$result = $this->retStripe->tstRetour($amount, $id, $idCommande, $mailCommande);
		
		// Code du cas non passant et non controlé en formulaire : 4100000000000019
		//Validation est vrai si le payment s'est bien passé
		if ($result){
			
			$commande->setPayer(true);
			$this->entityManager->flush();
			
			$message = (new \Swift_Message('Musée du Louvre - Vos Billets'))
				->setFrom('romain.kasp@gmail.com')
				->setTo($mailCommande)
				->setBody(
							$this->renderView('confirmation/mail.html.twig',
												array('datCom' => $dateCommande->format('d-m-Y'),
												'datVisite' => $commande->getDateVisite()->format('d-m-Y'), 
												'numCom' => $idCommande, 
												'tabRecap' => $tabRecap,)
												),
							'text/html'
							);
			$mailer->send($message);
			
			return $this->render('confirmation/paiementOk.html.twig', [
								'tabRecap' => $tabRecap,
								]);
		}else{
			return $this->render('confirmation/paiementKo.html.twig', [
								'tabRecap' => $tabRecap,
								]);	
		}
    }
}
