<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Ticket;

class RetourStripe
{
    public function tstRetour($amount, $id, $idCommande, $mailCommande)
    {
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
		
		return $validation && $charge->paid;
    }
}