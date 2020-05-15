<?php

// récupération des variables
$NUMS = $_POST['NUMS'];
$NOSERIE = $_POST['NOSERIE'];
$NODOSSIER = $_POST['NODOSSIER'];


$titre = "Création du ticket no° $NOSERIE du spectacle no° $NUMS ";
include('entete.php');

/* $date = date( "Y-m-d H:i:s", time());
echo "$date";

$date1 =  */
print_r($_POST);

/* $datetime1 = new DateTime();
$datetime2 = new DateTime($dateRep);
$interval = $datetime1->diff($datetime2);
$elapsed = $interval->format(' %h hours');
echo $elapsed; */

    // requete pour recuperer les dates de spectacles
    $requete = ("
            SELECT to_char(daterep,'DD-MM-YYYY HH24:MI') as DATEREP from LesRepresentations
            where numS='$NUMS' 
        ");
        
    // requete pour verifier le nombre de place disponible

    $requete2 = ( "
        select 1000 - count(*) as nb_res
        from LesTickets
        where dateRep = to_date('$dateRep', 'YYYY-MM-DD hh24:mi');
    ");

	// analyse de la requete et association au curseur
    $curseur = oci_parse($lien, $requete);
    
	// execution de la requete
	$ok = @oci_execute($curseur);

	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";
	} else {

		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch($curseur);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Aucune représentation pour le spectacle no° $NUMS</b></p>";
		} else {
			// on affiche le formulaire de sélection
			echo'
					<form action="creation_res_action2.php" method="post">
					<label for="sel_dateRep">Sélectionnez une représentation :</label>
                    <select id="sel_dateRep" name="dateRep">
				';

            // création des options
            
			do {
                $dateRep = oci_result($curseur, 1);
                $curseur2 = oci_parse($lien, $requete2);
                $ok2 = @oci_execute($curseur2);
                if (!$ok2) {
                    $error_message = oci_error($curseur2);
                    echo "<p class=\"erreur\">{$error_message['message']}</p>";
                }else {
                    $res2 = oci_fetch($curseur2);
                    if (!$res2) {
                        echo "<p class=\"erreur\"><b>Aucune place</b></p>";
                    }else {
                        $nb_disp = oci_result($curseur2, 1);
                        echo"$nb_disp";
                        echo ("<option value=\"$dateRep\">yolo/option>");                       
                    }
                }

            
			} while ($res = oci_fetch($curseur));

            echo '
                    </select>
                    <br /><br />
                    <input type="submit" value="Valider" />
                    <input type="reset" value="Annuler" />
                </form>
				';
		}
	}
    
	// on libère le curseur
    oci_free_statement($curseur);
   
    //info programme
    echo ( "
		<p class=\"info\">
            Si aucune date ne s'affiche alors aucune representation ne respecte les contraintes suivantes:
            <br></br>
            -    le nombre de places disponibles est inferieur a 70;
            <br></br>
            -    la date de la representation est > a t - 1 heure. 
		</p>
	");

include('pied.php');
