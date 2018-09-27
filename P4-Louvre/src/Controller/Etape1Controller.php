<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Handlers\Forms\Etape1FormHandler;

class Etape1Controller extends Controller
{
    private $etape1FormHandler;
	
    public function __construct(Etape1FormHandler $etape1FormHandler)
    {
        $this->etape1FormHandler = $etape1FormHandler;
    }	
	
    /**
     * @Route("/", name="step1")
     */
    public function index(Request $request)
    {
		$message = "";
		$commande = new Commande();
	
		$form = $this->createForm(CommandeType::class, $commande)->handleRequest($request);
		$resultHandler = $this->etape1FormHandler->handle($form);

		if ($resultHandler==0)
			return $this->redirectToRoute('step2');
		else if ($resultHandler > 100){
			$messages = $this->getParameter('messageErreur');
			$message = $messages[$resultHandler];
		}
				
        return $this->render('etape1/index.html.twig', [
			'message' => $message,
			'form' => $form->createView(),
        ]);
    }
}
