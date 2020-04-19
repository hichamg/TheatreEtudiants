<?php

	$titre = 'Liste des places associées au dossier 11 pour une catégorie donnée';
	include('entete.php');

	// affichage du formulaire
	echo ( "
		<form action=\"SpectaclesDossier_v1_action.php\" method=\"POST\">
			<label for=\"inp_categorie\">Veuillez Choisir une catégorie :</label>
			
			<input type=\"radio\" name=\"categorie\" value=\"orchestre\"/>
  			<label for=\"orchestre\">Orchestre</label>
			
			<input type=\"radio\" name=\"categorie\" value=\"1er balcon\"/>
  			<label for=\"1er balcon\">1er balcon</label>
			  
			<input type=\"radio\" name=\"categorie\" value=\"2nd balcon\"/>
  			<label for=\"2nd balcon\">2nd balcon</label>
			  
			<input type=\"radio\" name=\"categorie\" value=\"poulailler\"/>
			<label for=\"poulailler\">Poulailler</label>
			
			<br /><br />
			<input type=\"submit\" value=\"Valider\" />
			<input type=\"reset\" value=\"Annuler\" />
		</form>
	");

	// travail à réaliser
	echo ("
		<p class=\"work\">
			Améliorez l'interface utilisateur en proposant, à la place du champ de saisie libre, un choix de catégorie dans une liste contenant toutes les catégories (sous forme de boite de sélection ou de boutons radio).<br />Cette liste sera codée \"en dur\".
		</p>
	");

	include('pied.php');

?>
