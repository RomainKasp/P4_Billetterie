<?php

namespace App\Handlers\Forms;

use App\Service\Controles;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Etape1FormHandler
{
    private $controles;
    private $container;
    private $session;
	
    public function __construct(Controles $controles, ContainerInterface $container, SessionInterface $session)
    {
        $this->controles = $controles;
        $this->container = $container;
        $this->session = $session;
    }
	
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
			//Récupération des données
			$commande = $form->getData();		
			$nombreTicket = $form['number_ticket']->getNormData();
			
			$ctlForm= $this->controles->ctlForm( $commande, $nombreTicket, $this->container);
			
            if ($ctlForm == 0){
                //Mise en mémoire des sessions
                $this->session->set('commande', $commande);
                $this->session->set('nbrTicke', $nombreTicket);
            }
			return $ctlForm;
        }
        return 1;
    }
}