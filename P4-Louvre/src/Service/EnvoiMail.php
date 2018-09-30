<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Ticket;

class EnvoiMail
{
	private $mailer;
	private $templating;
	
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }
	
    public function send($mailCommande, $dateCommande, $dateVisite, $idCommande, $tabRecap)
    {
		$message = (new \Swift_Message('Musée du Louvre - Vos Billets'))
			->setFrom('romain.kasp@gmail.com')
			->setTo($mailCommande)
			->setBody(
						$this->templating->render('confirmation/mail.html.twig',
													array('datCom' => $dateCommande->format('d-m-Y'),
													'datVisite' => $dateVisite->format('d-m-Y'), 
													'numCom' => $idCommande, 
													'tabRecap' => $tabRecap,)
												),
						'text/html'
						);
		$this->mailer->send($message);
    }
}