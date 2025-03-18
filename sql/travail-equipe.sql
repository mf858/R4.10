DROP SCHEMA IF EXISTS groupes CASCADE;
CREATE SCHEMA groupes;
SET SCHEMA 'groupes';

CREATE TABLE auteurs(
  pid varchar(50) PRIMARY KEY,
  nom VARCHAR(255)
);

CREATE TABLE publications (
    id INT PRIMARY KEY,
    score INT,
    titre VARCHAR(1000),
    lieu VARCHAR(50),
    annee INT,
    acces VARCHAR(200),
    format VARCHAR(50),
    url VARCHAR(500),
	domaine varchar(500)
);

CREATE TABLE publication_auteurs (
    publication_id INT,
    auteur_pid varchar(50),
    FOREIGN KEY (publication_id) REFERENCES publications(id),
    FOREIGN KEY (auteur_pid) REFERENCES auteurs(pid),
    PRIMARY KEY (publication_id, auteur_pid)
);
