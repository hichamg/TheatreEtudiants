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

    //requete pour rajouter la representation 
        $requete2 = "INSERT INTO LesRepresentations (dateRep, numS) values ( TO_DATE('$date', 'YYYY-MM-DD HH24:MI'), $numS )";
    
    //requete ppour modifier les tickets 
        $requete3 = "UPDATE LesTickets
                    SET dateRep=TO_DATE('$date', 'YYYY-MM-DD HH24:MI')
                    Where dateRep=TO_DATE('$dateRep', 'DD-MM-YYYY HH24:MI') ";

    // requete pour la suppression de la representation
        $requete4 = "DELETE FROM LesRepresentations
                    Where dateRep=TO_DATE('$dateRep', 'DD-MM-YYYY HH24:MI') ";

    $curseur = oci_parse ($lien, $requete) ;
    

    $ok = @oci_execute ($curseur, OCI_NO_AUTO_COMMIT) ;
    // on teste $ok pour voir si oci_execute s'est bien passé
    if (!$ok) {

        echo "Modification des Tickets pour la presentation choisis\n";
        $e = oci_error($curseur);
        if ($e['code'] == 1) {
            echo LeMessage ("affectationconnue") ;
        } else if($e['code']==2292) {
            //Ajout de la representation pour l'etape intermediaire
                $curseur2 = oci_parse ($lien2, $requete2) ;
                $ok = @oci_execute ($curseur2, OCI_NO_AUTO_COMMIT) ;
                if(!$ok){
                    $e = oci_error($curseur2);
                    if ($e['code'] == 1) {
                        echo LeMessage("affectationconnue");
                    } else {
                        echo LeMessageOracle($e['code'], $e['message']);
                    }
                    oci_rollback ($lien2) ;

                }else {
                    echo"okTest" ;
                    oci_commit($lien2);
                }
            
            //Modification de la dateRep pour les tickets 
                $curseur3 = oci_parse ($lien3, $requete3) ;
                $ok = @oci_execute ($curseur3, OCI_NO_AUTO_COMMIT) ;
                if(!$ok){
                    $e = oci_error($curseur3);
                    if ($e['code'] == 1) {
                        echo LeMessage("majRejetee");
                    } else {
                        echo LeMessageOracle($e['code'], $e['message']);
                    }
                    oci_rollback ($lien3) ;

                }else {
                    oci_commit($lien3);
                }    

            //Suppression de l'ancienne representaton 
                $curseur4 = oci_parse ($lien4, $requete4) ;
                $ok = @oci_execute ($curseur4, OCI_NO_AUTO_COMMIT) ;
                if(!$ok){
                    $e = oci_error($curseur4);
                    if ($e['code'] == 1) {
                        echo LeMessage( "representationconnue");
                    } else {
                        echo LeMessageOracle($e['code'], $e['message']);
                    }
                    oci_rollback ($lien4) ;

                }else {
                    oci_commit($lien4);
                }

        } else {
            echo LeMessageOracle ($e['code'], $e['message']) ;
            
        }

        // terminaison de la transaction : annulation
        oci_rollback ($lien) ;

    }
    else {

        echo LeMessage ("majOK") ;
        // terminaison de la transaction : validation
        oci_commit ($lien) ;

    }
    // on libère le curseur
    oci_free_statement($curseur);
    oci_free_statement($curseur2);
    oci_free_statement($curseur3);
    oci_free_statement($curseur4);
	include('pied.php');
