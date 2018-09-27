<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;

class ValidationController extends Controller
{
    private $session;
	private $entityManager;
	
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
	
    /**
     * @Route("/validation", name="validatePaiement")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
		$tabRecap 	= $this->session->get('tabRecap');
		$amount 	= $this->session->get('amount')*100;
		$commande 	= $this->session->get('commande');
		$id 			= $commande->getId();
		$commande 		= $this->entityManager->getRepository(Commande::class)->find($id);
		$mailCommande 	= $commande->getMail();
		$dateCommande	= $commande->getDateCommande();
		$idCommande 	= $commande->getCodeCommande();
		$validation 	= true;

		// Set your secret key: remember to change this to your live secret key in production
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey("sk_test_bo8KdDDBj7K4myPuwBt5rNnr");
		
		// Token is created using Checkout or Elements!
		// Get the payment token ID submitted by the form:
		$token = $_POST['stripeToken'];
		
		try {
			$charge = \Stripe\Charge::create([
				'amount' => $amount,
				'currency' => 'EUR',
				'description' => 'Commande '.$idCommande." de ". $mailCommande,
				'source' => $token,
				'metadata' => ['order_id' => $id ],
				'capture' => false,
			]);
			
			$charge->capture();
			//dump($charge);die;
		}catch(\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$validation 	= false;
		} catch (\Stripe\Error\RateLimit $e) {
			// Too many requests made to the API too quickly
			$validation 	= false;
		} catch (\Stripe\Error\InvalidRequest $e) {
			// Invalid parameters were supplied to Stripe's API
			$validation 	= false;
		} catch (\Stripe\Error\Authentication $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$validation 	= false;
		} catch (\Stripe\Error\ApiConnection $e) {
			// Network communication with Stripe failed
			$validation 	= false;			
		} catch (\Stripe\Error\Base $e) {
			// Display a very generic error to the user, and maybe send
			// yourself an email	
			$validation 	= false;			
		}catch (Exception $e) {
			$validation 	= false;
		}
		
		// Code du cas non passant et non controlé en formulaire : 4100000000000019
		//Validation est vrai si le payment s'est bien passé
		if ($validation && $charge->paid){
			
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
