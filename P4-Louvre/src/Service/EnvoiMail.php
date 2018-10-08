<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Ticket;
use Symfony\Component\Translation\TranslatorInterface;

class EnvoiMail
{
	private $mailer;
	private $templating;
	private $trans;
	
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, TranslatorInterface $trans)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->trans = $trans;
    }
	
    public function send($mailCommande, $dateCommande, $dateVisite, $idCommande, $tabRecap)
    {
		$objet =  $this->trans->trans('mail.objet');
		$message = (new \Swift_Message($objet))
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