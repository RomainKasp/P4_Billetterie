var date = new Date();
var jour = date.getDate();
var mois = date.getMonth()+ 1;
var ann = date.getFullYear();
if (jour<10)
	jour = "0" + jour;
if (mois<10)
	mois = "0" + mois;
	
date = ann + "-" + mois + "-" + jour;

document.getElementById("commande_dateCommande").value = date; 	

$( function() {

	
$( ".js-datepicke" ).datepicker({
		altField: ".js-datepicke",
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
		weekHeader: 'Semaine',
		dateFormat: 'yy-mm-dd'
	});		
$( ".js-datepicke" ).datepicker( "option", "minDate", new Date() );
} );