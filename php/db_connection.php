<?php
    // Informations de connexion
$host = 'localhost';  // Adresse du serveur
$dbname = 'postgres'; // Nom de la base de données
$username = 'username'; // Nom d'utilisateur PostgreSQL
$password = 'password'; // Mot de passe
$port = 5432; // Port par défaut de PostgreSQL

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Connexion réussie à PostgreSQL !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>