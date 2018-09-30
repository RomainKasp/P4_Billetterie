<?php

namespace App\Handlers\Forms;

use App\Service\Controles;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class Etape2FormHandler
{
    private $session;
	private $entityManager;
	
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
	
    public function handle(FormInterface $form)
    {
		if ($form->isSubmitted() && $form->isValid()) {	
			//$this->session = $session;
			$commande = $form->getData();
			$this->session->set('commande', $commande);
			
			//prépare la requête
			$this->entityManager->persist($commande);
			//execute la requete
			$this->entityManager->flush();
			
			return true;
		}	
		return false;
    }
}