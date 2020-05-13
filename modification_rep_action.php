<?php

	// récupération des variables
        $dateRep = $_POST['dateRep'];
        $dateNv = $_POST['dateNv'];
        $date = date("Y-m-d H:i",strtotime($dateNv)) ;
        $numS = $_POST['numS'];

	$titre = "Changement de date de la représentation du $dateRep vers $date";
	include('entete.php');
    
    // requete pour modification de la representation 
    $requete = "UPDATE LesRepresentations
                SET dateRep=TO_DATE('$date', 'YYYY-MM-DD HH24:MI')
                Where dateRep=TO_DATE('$dateRep', 'DD-MM-YYYY HH24:MI') ";



    $curseur = oci_parse ($lien, $requete) ;
    

    $ok = @oci_execute ($curseur, OCI_NO_AUTO_COMMIT) ;
    // on teste $ok pour voir si oci_execute s'est bien passé
    if (!$ok) {

        echo LeMessage("majRejetee") . "<br /><br />";
        $e = oci_error($curseur);
        if ($e['code'] == 1) {
            echo LeMessage("représentationconnue");
        }else if( $e['code'] == 2292){
            echo LeMessage("violationContarinte");
        } else {
            echo LeMessageOracle($e['code'], $e['message']);
        }

        // terminaison de la transaction : annulation
        oci_rollback($lien);
    } else {

        echo LeMessage("majOK");
        // terminaison de la transaction : validation
        oci_commit($lien);
    }
    // on libère le curseur
    oci_free_statement($curseur);

	include('pied.php');
