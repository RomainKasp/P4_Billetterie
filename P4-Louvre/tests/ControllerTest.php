<?php

namespace App\Controller;

class testEntite extends Controller
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
		$dateVisi = $commande->getDateVisite();
		$jourSemm = $commande->getJourSemaineCommande();
		
		$this->assertEquals($valeursTests["mail"], $mailComm);
		$this->assertEquals($valeursTests["dateCommande"], $dateComm);
		$this->assertEquals($valeursTests["dateVisite"], $dateVisi);
		$this->assertEquals($valeursTests["jourSemaine"], $jourSemm);
    }
	
	public function testRouteFormulaire1()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$this->assertEquals(200, $crawler->getResponse()->getStatusCode());
    }
	public function testRouteFormulaire2()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/tickets');
		$this->assertEquals(200, $crawler->getResponse()->getStatusCode());
    }
	public function testRoutePaiement()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/paiement');
		$this->assertEquals(500, $crawler->getResponse()->getStatusCode());
    }
	
	
	public function testRouteFormulaire1Bis()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$this->assertRegExp('Votre mail:', $crawler->getResponse()->getContent());
    }		
	
	public function testEnvoiFormulaire1()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$crawler = $client->submit($form, array('mail' => 'test@domain.com',
												'number_ticket' => 1,
												'dateVisite' => "2022-12-01"));
		$this->assertRegExp('Nom:', $crawler->getResponse()->getContent());
    }	
	
	public function testEchecFormulaire1()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$crawler = $client->submit($form, array('mail' => 'test@domain.com',
												'number_ticket' => 01,
												'dateVisite' => "2020-09-08"));
		$this->assertRegExp('Le musée est fermer le mardi', $crawler->getResponse()->getContent());
    }	
	
	public function testEchecFormulaire1Bis()
    {
		$client = static::createClient();
		$crawler = $client->request('GET', '/');
		$crawler = $client->submit($form, array('mail' => 'test@domain.com',
												'number_ticket' => 01,
												'dateVisite' => "2019-12-25"));
		$this->assertRegExp('Le musée est fermer ce jour férié', $crawler->getResponse()->getContent());
    }	
}