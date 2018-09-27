<?php
// config/services.php
//Nombres de places disponibles par jour
$container->setParameter('places.journ', 1000);

//Liste les tarifs applicables
$container->setParameter('tarifReduit', [	"Nom" => "reduit", 
											"Prix" => "10.00" ,    ]);
$container->setParameter('tarifs', [	[	"Nom" => "senior",
											"AgeMax" => "60",
											"Prix" => "12.00" , ],
										[	"Nom" => "normal",
											"AgeMax" => "12", 
											"Prix" => "16.00" , ],
										[	"Nom" => "enfant",
											"AgeMax" => "04", 
											"Prix" => "08.00" , ],
										[	"Nom" => "bas-age",
											"AgeMax" => "00", 
											"Prix" => "00.00" , ],
									]);
// Utilisation d'une ligne par mois (la première étant Janvier et la dernière Decembre)		
// Ne pas supprimer la valeur 00 (permet d'étalonner les mois)							
$container->setParameter('jourFerier',[	[00,] , 
										[00,] ,    
										[00,] ,    
										[00,] ,    
										[00,01,] ,    
										[00,] ,    
										[00,] ,    
										[00,] ,    
										[00,] ,    
										[00,] ,    
										[00,01,] ,    
										[00,25,] ]);
// Liste des messages d'erreurs		
//   possibilité d'ajouter les erreurs propres à chaque plantage de paiement par la suite							
$container->setParameter('messageErreur',[	101 => "Nombre de place desirée incorrect.",  
											102 => "Nombre de places disponibles insuffisantes pour cette date de visite.<br /> Veuillez choisir une autre date.<br /><br />",     
											103 => "Date de commande incorrecte",     
											104 => "Le musée est fermé le mardi.<br /> Veuillez choisir une autre date.<br /><br />",     
											105 => "Jour ferié.<br /> Veuillez choisir une autre date.<br /><br />",     
										]);										