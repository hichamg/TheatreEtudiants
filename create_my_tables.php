<?php

$titre = 'Création de la Base de Données';
include('entete.php');

    $requete[1]= "create table LesCategories (
                    nomC varchar(20),
                    prix number(8,2) not null,
                    constraint LesCategories_pk primary key (nomC),
                    constraint LesCategories_check check ( prix > 0 )
                )";

    $requete[2]= "create table LesZones (
                    numZ Number(4) not null,
                    nomC varchar(20),
                    constraint LesZones_pk primary key (numZ),
                    constraint LesZones_fk foreign key (nomC) references LesCategories(nomC),
                    constraint  LesZones_check check ( numZ > 0)
                )";
    
    $requete[3]= "create table LesPlaces (
                    noPlace number(4) not null,
                    noRang number(4) not null,
                    numZ number(4) not null,
                    constraint LesPlaces_pk primary key (noPlace, noRang),
                    constraint LesPlaces_fk foreign key (numZ) references LesZones (numZ),
                    constraint LesPlaces_check check ( noPlace>0 and noRang>0 and numZ>0)
                )";

    $requete[4]= "create table LesSpectacles (
                    numS number(4) not null ,
                    nomS varchar(20),
                    constraint LesSpectacles_pk primary key (numS),
                    constraint LesSpectacles_check check ( numS > 0 )
                )";

    $requete[5]= "create table LesRepresentations (
                    dateRep date,
                    numS number(4) not null,
                    constraint LesRepresentations_pk primary key (dateRep),
                    constraint LesRepresentations_check check ( numS > 0 )
                )";

    $requete[6]= "create table LesDossiers (
                    noDossier number(4) not null,
                    montant number(8,2) not null,
                    constraint LesDossiers_pk primary key (noDossier),
                    constraint LesDossiers_check check ( noDossier > 0 )
                )";

    $requete[7]= "create table LesTickets (
                    noSerie number(4) not null,
                    numS number(4) not null,
                    dateRep date,
                    noPlace number(4) not null,
                    noRang number(4) not null,
                    dateEmission date,
                    noDossier number(4) not null,
                    constraint LesTickets_pk primary key (noSerie, numS, dateRep, noPlace, noRang),
                    constraint LesTickets_fk1 foreign key (dateRep) references LesRepresentations(dateRep),
                    constraint LesTickets_fk2 foreign key (noPlace, noRang) references LesPlaces(noPlace, noRang),
                    constraint LesTickets_fk3 foreign key (noDossier) references LesDossiers(noDossier),
                    constraint LesTickets_check1 check (numS>0 and noSerie>0 and noPlace>0 and noRang>0 and noDossier>0),
                    constraint LesTickets_check2 check ( dateEmission < dateRep )
                )";
    $lesRelations = array ("LesCategories","LesZones", "LesPlaces","LesSpectacles","LesRepresentations","LesDossiers","LesTickets");
    for ($i=1; $i<=7 ; $i++){
        $curseur = oci_parse ($lien, $requete[$i]) ;
        $ok=@oci_execute ($curseur);
        if (!$ok) {
					 // oci_execute a échoué, on affiche l'erreur
					$error_message = oci_error($curseur1);
                    echo "<p class=\"erreur\">{$error_message['message']}<b>Impossible de creer la relation ou la relation existe déja</b></p>";

				} 
				else {	
                    echo "Création de la table ".$lesRelations[$i]." réussie" ;
                    echo '<br/>' ;
				}
    }

include('pied.php');
