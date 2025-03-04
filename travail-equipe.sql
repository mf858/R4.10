DROP SCHEMA IF EXISTS analyse CASCADE;
CREATE SCHEMA analyse;
SET SCHEMA 'analyse';

CREATE TABLE analyse.auteurs(
  pid INT PRIMARY KEY
  nom VARCHAR(255),
);

CREATE TABLE analyse.publications (
    id INT PRIMARY KEY,
    score INT,
    titre VARCHAR(1000),
    année INT,
    type VARCHAR(200),
    format VARCHAR(50),
    url VARCHAR(500)
);

CREATE TABLE analyse.publication_auteurs (
    publication_id INT,
    auteur_pid VARCHAR(50),
    FOREIGN KEY (publication_id) REFERENCES publications(id),
    FOREIGN KEY (auteur_pid) REFERENCES auteurs(pid),
    PRIMARY KEY (publication_id, auteur_pid)
);
