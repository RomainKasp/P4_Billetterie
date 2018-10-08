<?php

namespace App\Controller;

use App\Service\Etape1Render;
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
     * @Route("/", name="step0")
     */
    public function routing()
    {
		return $this->redirectToRoute('step1');
    }	
	
    /**
     * @Route("/{_locale}/", name="step1")
     */
    public function index(Request $request, $messageErreur, Etape1Render $etape1Render)
    {
		$commande = new Commande();
	
		$form = $this->createForm(CommandeType::class, $commande)->handleRequest($request);
		$resultHandler = $this->etape1FormHandler->handle($form);

		if ($resultHandler==0)
			return $this->redirectToRoute('step2');
		else {
		    return $etape1Render->render($messageErreur, $form, $resultHandler);
		}
    }
}
