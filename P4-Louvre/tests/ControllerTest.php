<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class testEntite extends WebTestCase
{
    public function ControllerTest()
    {
		$valeursTests=[	"mail" 			=> "test@domain.com",
						"dateCommande"  => new date("2018-01-01"),
						"dateVisite"	=> new date("2018-06-06"),
						"jourSemaine"	=> 3,
						];
						
		$commande = new Commande();
		$commande->setMail($valeursTests["mail"]);
		$commande->setDateCommande($valeursTests["dateCommande"]);
		$commande->setDateVisite($valeursTests["dateVisite"]);
		
		$mailComm = $commande->getMail();
		$dateComm = $commande->getDateCommande();
		$jourSemm = $commande->getJourSemaineCommande();
        static::assertEquals($valeursTests["mail"], $mailComm);
        static::assertEquals($valeursTests["dateCommande"], $dateComm);
        static::assertEquals($valeursTests["jourSemaine"], $jourSemm);
    }
	
	public function testRouteFormulaire1()
    {
		$client = static::createClient();
		$client->request('GET', '/');
		static::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
	public function testRouteFormulaire2()
    {
		$client = static::createClient();
		$client->request('GET', '/tickets');
        static::assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
	public function testRoutePaiement()
    {
		$client = static::createClient();
		$client->request('GET', '/paiement');
        static::assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
    }
	public function testRouteFormulaire1Bis()
    {
		$client = static::createClient();
		$client->request('GET', '/');
        static::assertContains('Votre mail:', $client->getResponse()->getContent());
    }
	public function testEnvoiFormulaire1()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$form = $crawler->selectButton('Etape suivante')->form();
		$form['commande[mail]'] = 'test@domain.com';
		$form['commande[number_ticket]'] = 1;
		$form['commande[dateVisite]'] = "2022-12-01";
		$client->submit($form);
        static::assertContains('Nom:', $client->getResponse()->getContent());
    }
	public function testEchecFormulaire1()
    {
		$client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Etape suivante')->form();
        $form['commande[mail]'] = 'test@domain.com';
        $form['commande[number_ticket]'] = 1;
        $form['commande[dateVisite]'] = "2020-09-08";
        $client->submit($form);
        static::assertContains('mardi', $client->getResponse()->getContent());
    }
	public function testEchecFormulaire1Bis()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Etape suivante')->form();
        $form['commande[mail]'] = 'test@domain.com';
        $form['commande[number_ticket]'] = 1;
        $form['commande[dateVisite]'] = "2019-12-25";
        $client->submit($form);
        static::assertContains('férié', $client->getResponse()->getContent());
    }
}