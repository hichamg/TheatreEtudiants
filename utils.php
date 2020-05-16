<?php

	// les utilitaires pour la gestion du Theatre
	putenv("ORACLE_SID=im2ag");

	// les messages d'erreurs
	function LeMessage ($codeErreur) {
		$t = 1;
		switch ($codeErreur) {
			case "pasdeconnexion": 
				$t = 0;
				$message= "La connexion à la base de données a échoué"; break;
			case "connexionOK":
				$t = 1;
				$message= "Connexion réussie"; break;
			case "violationContarinte":
				$t = 1;
				$message= "Des reservations existent pour cette representation.La modification ne peut pas etre effectuée."; break;
			case "violationContarinte2":
				$t = 1;
				$message= "Impossible de modifier ce ticket,seuls les tickets des spectacles à venir peuvent etre modifiés.
					(Violation de la contrainte \"dateEmission &lt; dateRep\" "; break;
			case "majOK":
				$t = 1;
				$message = "La mise à jour a été effectuée"; break ;
			case "spectacleconnu":
				$t = 0;
				$message = "Spectacle déjà enregistré" ; break ;
			case "representationconnue":
				$t = 0;
				$message = "Representation déjà enregistrée" ; break ;
			case "affectationconnue":
				$t = 0;
				$message = "Afectation déjà enregistrée" ; break ;
			case "majRejetee":
				$t = 0;
				$message = "La mise à jour a été rejetée"; break ;
			case "NvDossier_impossible":
				$t = 0;
				$message = "Impossible de créer un nouveau dossier"; break ;
			case "problemevariables":
				$t = 0;
				$message = "Votre navigateur ne semble pas accepter les cookies"; break ;
			default:
				$t = 0;
				$message = "Autre message" ; break ;
		}
		$type = $t == 0 ? "erreur" : "info";
		return "<P class=\"$type\"><b>".$message."</b></P>";
	}

	// les messages d'erreur Oracle
	function LeMessageOracle ($codeOracle, $messageOracle) {
		switch ($codeOracle) {
			case 1:
				$message = "-> contrainte de clef non respectée"; break ;
			case 1400:
				$message = "-> valeur absente interdite"; break ;
			case 1722:
				$message = "-> erreur de type, un nombre était attendu"; break ;
			case 2291:
				$message = "-> contrainte référentielle non respectée"; break;
			default:
				$message = "-> autre message : ".$codeOracle; break ;
		}
		$message = "<b>".$message ;
		$message .= " (".$messageOracle.") </b>" ;
		return $message ;
	}

	// Connexion : une action (deux donnees, deux resultats)
	// Connexion (n, p, c, r) etablit une connexion oracle pour
	// l'utilisateur de nom n et de mot de passe p. c est le lien
	// vers la connexion et r est le code erreur associe.

	function Connexion ($nom, $motdepasse, &$lien, &$codeerreur) {
		// @ devant le nom de l'action neutralise les messages d'erreur
		// qu'elle pourrait envoyer

	  $host = 'im2ag-oracle.e.ujf-grenoble.fr';
	  $port = '1521';
	  $service_name = 'im2ag';

	  $lien = oci_connect($nom, $motdepasse, "//$host:$port/$service_name");
	  if ($lien)
	    $codeerreur = "connexionOK" ;
	  else
	    $codeerreur = "pasdeconnexion" ;
	}

	// Deconnexion : une action (une donnee)
	// Deconnexion (l) deconnecte le lien l de Oracle
	function Deconnexion ($lien) {
		oci_close ($lien) ;
	}

	// MaJ des Dossier
	function MajLesDossiers($NODOSSIER,$lien) {
            $requete = "
                UPDATE LesDossiers
                set montant=(
                    select sum(prix) as montant
                    from LesTickets
                            natural join LesPlaces
                            natural join LesZones
                            natural join LesCategories
                    where noDossier =:n)
                where noDossier =:n
            ";

        $curseur = oci_parse($lien, $requete);
        
        oci_bind_by_name($curseur, ':n', $NODOSSIER);
        $ok = oci_execute($curseur);
        if (!$ok) {

            echo LeMessage("majRejetee") . "<br /><br />";
            $e = oci_error($curseur);
            if ($e['code'] == 1) {
                echo LeMessage("majRejetee");
            } else {
                $a = $e['code'];
                echo LeMessageOracle($e['code'], $e['message']);
            }

            // terminaison de la transaction : annulation
            oci_rollback($lien);
        } else {
            //echo LeMessage("majOK");
            echo "<p class=\"ok\">
                La dossier $NODOSSIER est mis a jour avec succès.
                </p> ";
            // terminaison de la transaction : validation
            oci_commit($lien);
        }

        oci_free_statement($curseur);
	}

?>
