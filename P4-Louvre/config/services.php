<?php
// config/services.php
//Nombres de places disponibles par jour
$container->setParameter('places.journ', 1000);

//Liste les tarifs applicables
$container->setParameter('tarifReduit', [	"Nom" => "tarifs.reduit", 
											"Prix" => "10.00" ,    ]);
$container->setParameter('tarifs', [	[	"Nom" => "tarifs.senior",
											"AgeMax" => "60",
											"Prix" => "12.00" , ],
										[	"Nom" => "tarifs.normal",
											"AgeMax" => "12", 
											"Prix" => "16.00" , ],
										[	"Nom" => "tarifs.enfant",
											"AgeMax" => "04", 
											"Prix" => "08.00" , ],
										[	"Nom" => "tarifs.basage",
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
$container->setParameter('messageErreur',[	101 => "mess.nbrPlace",  
											102 => "mess.nbrPlace2",     
											103 => "mess.datComm",     
											104 => "mess.mardi",     
											105 => "mess.ferie",     
										]);										