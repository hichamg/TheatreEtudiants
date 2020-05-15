<?php

// récupération des variables
$NUMS = $_POST['NUMS'];
$NOSERIE = $_POST['NOSERIE'];
$NODOSSIER = $_POST['NODOSSIER'];
$DATEREP = $_POST['dateRep'];

$titre = "Création du ticket no° $NOSERIE du spectacle no° $NUMS [$DATEREP] ";
include('entete.php');
    if ($NODOSSIER==0) {
        $requete1= " SELECT max(noDossier)+1 from LesDossiers";
        $curseur1 = oci_parse($lien, $requete1);
        $ok = oci_execute($curseur1);
        if (!$ok) {
            // oci_execute a échoué, on affiche l'erreur
            $error_message = oci_error($curseur1);
            echo "<p class=\"erreur\">{$error_message['message']}</p>";
        } else {
            // oci_execute a réussi, on fetch sur le premier résultat
            $res = oci_fetch($curseur1);
            if (!$res) {
                // il n'y a aucun résultat
                echo "<p class=\"erreur\"><b>Impossible de trouver le nouveau numero de dossier</b></p>";
            } else {
                $NODOSSIER = oci_result($curseur1, 1);
                //echo "<input type=\"hidden\" id=\"sel_NODOSSIER\" name=\"NODOSSIER\" value=\"$NODOSSIER\" />";
                
            }
        }
        oci_free_statement($curseur1);

    }

    //print_r($_POST);
    // requete pour retrouver
    $requet = "
        select noPlace, noRang from LesPlaces
        minus
        select noPlace, noRang from LesTickets
        where dateRep = TO_DATE('2020-11-11 21:00', 'YYYY-MM-DD HH24:MI')
    ";

    // analyse de la requete et association au curseur
    $curseur = oci_parse($lien, $requete);
    // affectation
    //oci_bind_by_name($curseur, ':n', $DATEREP);
	// execution de la requete
    $ok = oci_execute($curseur);
    // on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {
		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";
	} else {

		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch($curseur); // ORA-24338: statement handle not executed

		if (!$res) {
			// il n'y a aucun résultat
            echo "<p class=\"erreur\"><b>Aucune place disponible pour la representation du $DATEREP</b></p>";
            $e = oci_error($curseur);
			echo LeMessageOracle ($e['code'], $e['message']) ;
		} else {
			// on affiche le formulaire de sélection
			echo'
					<form action="creation_res_action2.php" method="post">
					<label for="sel_pRang">Sélectionnez un siége (place/rang) :</label>
                    <select id="sel_pRang" name="pRang">
				';
            $noPlace = oci_result($curseur, 1); // 
            $noRANG = oci_result($curseur, 2);
            // création des options
            echo ("<option value=\"$pRang\">$noPlace</option>");
            
			do {
                $noPlace = oci_result($curseur, 1);
                $noRANG = oci_result($curseur, 2);
                list($noPlace, $noRANG) = $pRang;
                echo ("<option value=\"$pRang\">$noPlace</option>");                       
                    
			} while ($res = oci_fetch($curseur));

            echo "
                    </select>
                    <br /><br />
                    <input type=\"hidden\" id=\"sel_NUMS\" name=\"NUMS\" value=\"$NUMS\" />
                    <input type=\"hidden\" id=\"sel_NODOSSIER\" name=\"NODOSSIER\" value=\"$NODOSSIER\" />
                    <input type=\"hidden\" id=\"sel_NOSERIE\" name=\"NOSERIE\" value=\"$NOSERIE\" />
                    <input type=\"submit\" value=\"Valider\" />
                    <input type=\"reset\" value=\"Annuler\" />
                </form>
				";
		}
	}
    
	// on libère le curseur
    oci_free_statement($curseur);
//info programme
echo ("
		<p class=\"info\">
            Si aucune date ne s'affiche alors aucune representation ne respecte les contraintes suivantes:
            <br></br>
            -    le nombre de places disponibles est inferieur a 70;
            <br></br>
            -    la date de la representation est > a t - 1 heure. 
		</p>
	");

include('pied.php');
