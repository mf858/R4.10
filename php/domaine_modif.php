<?php
// Connexion à PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "dblp_db";
$user = "postgres";
$password = "password";  // Remplacez par votre mot de passe PostgreSQL

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("❌ Échec de la connexion à PostgreSQL");
}

// Étape 1 : Vérifier si la colonne "lien" existe
$sql_check_column = "SELECT column_name FROM information_schema.columns WHERE table_name='publications' AND column_name='lien'";
$result = pg_query($conn, $sql_check_column);
if (pg_num_rows($result) > 0) {
    // Renommer la colonne "lien" en "domaines"
    $sql_rename_column = "ALTER TABLE publications RENAME COLUMN lien TO domaines";
    pg_query($conn, $sql_rename_column);
    echo "✔️ Colonne 'lien' renommée en 'domaines'.<br>";
}

// Tableau de correspondance Venue → Domaine
$venue_to_domain = [
    "Comput. Phys. Commun." => "Physique Computationnelle",
    "Frontiers Artif. Intell." => "Intelligence Artificielle",
    "Nat." => "Sciences Générales",
    "Comput. Softw. Big Sci." => "Big Data & Informatique Scientifique",
    "CoRR" => "Prépublications en Informatique",
    "NeuroImage" => "Neurosciences & Imagerie Médicale",
    "VLSI Technology and Circuits" => "Conception de Circuits (VLSI)",
    "SC" => "Supercomputing (HPC)",
    "Comput. Appl. Math." => "Mathématiques Appliquées",
    "BMC Medical Informatics Decis. Mak." => "Informatique Médicale",
    "npj Digit. Medicine" => "Médecine Numérique",
    "WINCOM" => "Réseaux Sans Fil",
    "Sensors" => "Capteurs et Systèmes Intelligents",
    "Appl. Math. Lett." => "Mathématiques Appliquées",
    "CINAIC" => "Intelligence Artificielle & Éducation",
    "Comput. Sci. Rev." => "Revue Générale en Informatique",
    "Future Internet" => "Réseaux & Internet du Futur",
    "WITCOM" => "Communications & Réseaux",
    "BROADNETS" => "Réseaux de Communication",
    "Int. J. Bifurc. Chaos" => "Mathématiques - Théorie du Chaos",
    "CIARP" => "Reconnaissance des Formes & Vision",
    "WISATS" => "Satellites & Télécommunications",
    "PSATS" => "Télécommunications par Satellite",
    "Axioms" => "Mathématiques & Logique",
    "Database J. Biol. Databases Curation" => "Bases de Données Biologiques",
    "BMC Bioinform." => "Bioinformatique",
    "Int. J. Medical Informatics" => "Informatique Médicale",
    "Nucleic Acids Res." => "Biologie Moléculaire",
    "IEEE Access" => "Revue Multidisciplinaire",
    "J. Vis. Commun. Image Represent." => "Vision par Ordinateur & Compression",
    "SMACD" => "Conception de Circuits Intégrés",
    "UCET" => "Ingénierie & Technologie",
    "J. Parallel Distributed Comput." => "Calcul Parallèle & Distribué",
    "CIT/IUCC/DASC/PICom" => "Informatique & Systèmes Distribués",
    "IAS" => "Informatique & Systèmes Intelligents",
    "IDT" => "Technologies de l’Information",
    "Int. J. Inf. Manag." => "Gestion de l’Information",
    "Telecommun. Syst." => "Télécommunications"
];

// Étape 2 : Mise à jour des valeurs de "domaines"
foreach ($venue_to_domain as $venue => $domaine) {
    // Mise à jour exacte
    $sql_update_exact = "UPDATE publications SET domaines = '$domaine' WHERE domaines = '$venue'";
    pg_query($conn, $sql_update_exact);

    // Mise à jour approximative avec ILIKE
    $sql_update_like = "UPDATE publications SET domaines = '$domaine' WHERE domaines ILIKE '%$venue%'";
    pg_query($conn, $sql_update_like);
}

// Fermer la connexion PostgreSQL
pg_close($conn);
?>
