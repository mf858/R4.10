<?php
$host = 'localhost'; // Adresse du serveur PostgreSQL
$dbname = 'postgres'; // Nom de votre base de données
$user = 'postgres'; // Nom d'utilisateur PostgreSQL
$password = 'Baptiste85'; // Mot de passe de l'utilisateur

try {
    // Création d'une connexion PDO
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Définit le mode de récupération des résultats en tableau associatif
        PDO::ATTR_EMULATE_PREPARES   => false, // Désactive l'émulation des requêtes préparées
    ];

    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Connexion réussie à la base de données PostgreSQL.";
} catch (PDOException $e) {
    // Gestion des erreurs
    die("Erreur de connexion : " . $e->getMessage());
}
?>
