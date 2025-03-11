<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dblp_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Étape 1 : Renommer la colonne "lien" en "domaines" (si elle existe)
$sql_check_column = "SHOW COLUMNS FROM publications LIKE 'lien'";
$result = $conn->query($sql_check_column);
if ($result->num_rows > 0) {
    $sql_rename_column = "ALTER TABLE publications CHANGE lien domaines VARCHAR(255);";
    if ($conn->query($sql_rename_column) === TRUE) {
        echo "✔️ Colonne 'lien' renommée en 'domaines'.<br>";
    } else {
        echo "❌ Erreur : " . $conn->error . "<br>";
    }
}

// Tableau de correspondance (peut être stocké dans une table SQL)
$conference_to_domain = [
    "NeurIPS" => "Machine Learning",
    "ICML" => "Machine Learning",
    "AAAI" => "Intelligence Artificielle",
    "IJCAI" => "Intelligence Artificielle",
    "CVPR" => "Computer Vision",
    "ICCV" => "Computer Vision",
    "ECCV" => "Computer Vision",
    "ICRA" => "Robotique",
    "IROS" => "Robotique",
    "ACL" => "Traitement du Langage Naturel",
    "NAACL" => "Traitement du Langage Naturel",
    "EMNLP" => "Traitement du Langage Naturel",
    "SIGMOD" => "Bases de Données",
    "VLDB" => "Bases de Données",
    "ICDE" => "Bases de Données",
    "INFOCOM" => "Réseaux Informatiques",
    "SIGCOMM" => "Réseaux Informatiques",
    "CCS" => "Cybersécurité",
    "ICSE" => "Génie Logiciel",
    "FSE" => "Génie Logiciel",
    "RTSS" => "Systèmes Temps Réel",
    "AAMAS" => "Agents Autonomes",
    "GECCO" => "Algorithmes Évolutionnaires",
    "CHI" => "Interaction Homme-Machine",
    "OSDI" => "Systèmes d'Exploitation",
    "SOSP" => "Systèmes d'Exploitation",
    "IEEE Trans. Fuzzy Syst." => "Systèmes Flous",
    "Wirel. Pers. Commun." => "Réseaux Sans Fil"
];

// Étape 2 : Mettre à jour la colonne "domaines" avec les bonnes valeurs
foreach ($conference_to_domain as $conference => $domaine) {
    // Correspondance exacte
    $sql_update_exact = "UPDATE publications SET domaines = '$domaine' WHERE domaines = '$conference'";
    $conn->query($sql_update_exact);

    // Correspondance partielle avec LIKE (MySQL)
    $sql_update_like = "UPDATE publications SET domaines = '$domaine' WHERE domaines LIKE '%$conference%'";
    $conn->query($sql_update_like);
}

// Étape 3 : Vérifier les valeurs non mises à jour
$sql_check_missing = "SELECT id, domaines FROM publications WHERE domaines NOT IN ('" . implode("','", array_values($conference_to_domain)) . "')";
$result = $conn->query($sql_check_missing);

if ($result->num_rows > 0) {
    echo "⚠️ Publications sans correspondance :<br>";
    while ($row = $result->fetch_assoc()) {
        echo "❌ ID: " . $row["id"] . " - Nom original: " . $row["domaines"] . "<br>";
    }
}

// Fermer la connexion
$conn->close();
?>
