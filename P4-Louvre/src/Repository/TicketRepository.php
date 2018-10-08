<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
	private $commande;
	
    public function __construct(RegistryInterface $registry, CommandeRepository $commande)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Retourne le nombre de tickets 
     */	
    public function nombreTicketParDate($dateVisite) 
    {
		$nbr=$this->createQueryBuilder('t')
			->select('count(t.id)')
		  	->innerjoin('App\Entity\Commande','c','WITH','c.dateVisite = :dateVisite')
            ->andWhere('c.id = t.commande')
            ->setParameter('dateVisite', $dateVisite)
            ->getQuery()
            ->getOneOrNullResult();
			
		if(is_null($nbr))	return 0;
		else				return $nbr[1];
    }
}
