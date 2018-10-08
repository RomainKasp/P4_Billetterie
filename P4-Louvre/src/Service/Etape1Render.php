<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Etape1Render
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * Etape1Render constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    public function render($messageErreur, $form, $resultHandler)
    {
        return new Response(
            $this->twig->render(
                'etape1/index.html.twig', [
                    'message' => $messageErreur[$resultHandler],
                    'form' => $form->createView(),
                ]
            )
        );
    }
}