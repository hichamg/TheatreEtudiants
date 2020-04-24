create table LesCategories (
    nomC varchar(20),
    prix number(8,2) not null,
    constraint LesCategories_pk primary key (nomC),
    constraint LesCategories_check check ( prix > 0 )
);

create table LesZones (
    numZ Number(4) not null,
    nomC varchar(20),
    constraint LesZones_pk primary key (numZ),
    constraint LesZones_fk foreign key (nomC) references LesCategories(nomC),
    constraint  LesZones_check check ( numZ > 0)
);

create table LesPlaces (
    noPlace number(4) not null,
    noRang number(4) not null,
    numZ number(4) not null,
    constraint LesPlaces_pk primary key (noPlace, noRang),
    constraint LesPlaces_fk foreign key (numZ) references LesZones (numZ),
    constraint LesPlaces_check check ( noPlace>0 and noRang>0 and numZ>0)
);

create table LesSpectacles (
    numS number(4) not null ,
    nomS varchar(20),
    constraint LesSpectacles_pk primary key (numS),
    constraint LesSpectacles_check check ( numS > 0 )
);

create table LesRepresentations (
    dateRep date,
    numS number(4) not null,
    constraint LesRepresentations_pk primary key (dateRep),
    -- on rajoute contrainte unique pour resoudre errer ORA-02270
    -- lors de la creation de la table LesTickets
    constraint LesRepresentations_unique unique (dateRep,numS),
    constraint LesRepresentations_check check ( numS > 0 )
);

create table LesDossiers (
    noDossier number(4) not null,
    montant number(8,2) not null,
    constraint LesDossiers_pk primary key (noDossier),
    constraint LesDossiers_check check ( noDossier > 0 )
);

create table LesTickets (
    noSerie number(4) not null,
    numS number(4) not null,
    dateRep date,
    noPlace number(4) not null,
    noRang number(4) not null,
    dateEmission date,
    noDossier number(4) not null,
    constraint LesTickets_pk primary key (noSerie, numS, dateRep, noPlace, noRang),
    constraint LesTickets_fk1 foreign key (numS, dateRep) references LesRepresentations(numS, dateRep),
    constraint LesTickets_fk2 foreign key (noPlace, noRang) references LesPlaces(noPlace, noRang),
    constraint LesTickets_fk3 foreign key (noDossier) references LesDossiers(noDossier),
    constraint LesTickets_check1 check (numS>0 and noSerie>0 and noPlace>0 and noRang>0 and noDossier>0),
    constraint LesTickets_check2 check ( dateEmission < dateRep )
);

