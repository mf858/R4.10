<?php

// Informations de connexion
$host = 'localhost';  // Adresse du serveur
$dbname = 'nom_de_la_base'; // Nom de la base de données
$username = 'postgres'; // Nom d'utilisateur PostgreSQL
$password = 'mot_de_passe'; // Mot de passe
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


    $file = file_get_contents("publ.json");
    $decoded_data = json_decode($file, true);
    $data = $decoded_data["result"]["hits"]["hit"];

    for($i = 0; $i < sizeof($data); $i++){
        //echo "<pre>";
        //print_r($data[$i]["info"]["title"]);
        //echo "<pre>";
        $id = $data[$i]["@id"];
        $score = $data[$i]["@score"];
        $titre = $data[$i]["info"]["title"];
        $lieux = $data[$i]["info"]["venue"];
        $annee = $data[$i]["info"]["year"];
        $format = $data[$i]["info"]["type"];
        $acces = $data[$i]["info"]["access"];


        $stmt = $pdo->prepare("insert into analyse.publications(id, score, titre, lieux, annee, acces, format, url
                            values(:id, :score, :titre, :lieux, :annee, :acces, :format, :url");
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':score',$score);
        $stmt->bindParam(':titre',$titre);
        $stmt->bindParam(':lieux',$lieux);
        $stmt->bindParam(':annee',$annee);
        $stmt->bindParam(':format',$format);
        $stmt->bindParam(':acces',$access);
        $stmt->bindParam(':url',$url);


    }



?>