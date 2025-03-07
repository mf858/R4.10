<?php
    // Informations de connexion
$host = 'localhost';  // Adresse du serveur
$dbname = 'postgres'; // Nom de la base de données
$username = 'postgres'; // Nom d'utilisateur PostgreSQL
$password = 'Baptiste85'; // Mot de passe
$port = 5432; // Port par défaut de PostgreSQL

try {
    // Création d'une instance PDO pour PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Définir le mode d'erreur de PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie à PostgreSQL !";
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message
    die("Erreur de connexion : " . $e->getMessage());
}

?>