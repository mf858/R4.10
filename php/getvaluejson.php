<?php
require_once("db_connection.php");

// Charger le fichier JSON contenant les publications
$file = file_get_contents("../json/publ.json");
$decoded_data = json_decode($file, true);
$data = $decoded_data["result"]["hits"]["hit"]; // Accès aux publications dans le JSON

$pid_auteurs = []; // Tableau pour stocker les auteurs 
$j = 0; // Compteur pour les affiliations des auteurs

for ($i = 0; $i < count($data); $i++) {
    $id = $data[$i]["@id"];
    $score = $data[$i]["@score"];
    $titre = $data[$i]["info"]["title"];
    $lieu = $data[$i]["info"]["venue"];
    $annee = $data[$i]["info"]["year"];
    $format = $data[$i]["info"]["type"];
    $acces = $data[$i]["info"]["access"];
    $adresse = $data[$i]["info"]["url"];
    $auteurs = $data[$i]["info"]["authors"]["author"];
    $doi = $data[$i]["info"]["doi"];

    // Recherche API OpenAlex pour récupérer des informations complémentaires
    $encoded_doi = urlencode($doi);
    print_r($encoded_doi);

    $url = "https://api.openalex.org/works/doi:" . $encoded_doi;
    print_r($url);  
    
    $response = file_get_contents($url);
    $domaine = "Inconnu"; // Valeur par défaut
    $authorship = [];

    if ($response !== FALSE) {
        $datalex = json_decode($response, true);
        
        //récupération du domaine
        if (isset($datalex['concepts']) && count($datalex['concepts']) > 0) {
            $domaine = $datalex["concepts"][0]["display_name"];
        }

        //récupération des auteurs
        if (isset($datalex['authorships'])) {
            $authorship = $datalex["authorships"];
        }
    }

    // Insertion des données de la publication dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO groupes.publications(id, score, titre, lieu, annee, acces, format, url, domaine)
        VALUES(:id, :score, :titre, :lieu, :annee, :acces, :format, :url, :domaine)
    ");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':lieu', $lieu);
    $stmt->bindParam(':annee', $annee);
    $stmt->bindParam(':format', $format);
    $stmt->bindParam(':acces', $acces);
    $stmt->bindParam(':url', $adresse);
    $stmt->bindParam(':domaine', $domaine);

    try {
        $stmt->execute();
        echo "✅ Données insérées pour l'ID : $id<br>";
    } catch (PDOException $e) {
        echo "❌ Erreur lors de l'insertion de l'ID $id : " . $e->getMessage() . "<br>";
    }

    // Parcours des auteurs associés à la publication
    foreach ($auteurs as $auth) {
        if (!in_array($auth["@pid"], $pid_auteurs)) { // Vérifier si l'auteur n'a pas déjà été inséré
            $affiliation = "Inconnu"; // Valeur par défaut

            // Vérification si une affiliation est disponible dans OpenAlex
            if (isset($authorship[$j]["institutions"][0]["display_name"])) {
                $affiliation = $authorship[$j]["institutions"][0]["display_name"];
            }

            // Insertion des auteurs dans PostgreSQL
            $stmt = $pdo->prepare("
                INSERT INTO groupes.auteurs(pid, nom, affiliation)
                VALUES(:pid, :nom, :affiliation)
            ");

            $stmt->bindParam(':pid', $auth["@pid"]);
            $stmt->bindParam(':nom', $auth["text"]);
            $stmt->bindParam(':affiliation', $affiliation);
            $j++;

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo "❌ Erreur lors de l'insertion de l'auteur PID " . $auth["@pid"] . " : " . $e->getMessage() . "<br>";
            }

            // Insertion de la relation entre publication et auteur
            $stmt = $pdo->prepare("
                INSERT INTO groupes.publication_auteurs(publication_id, auteur_pid)
                VALUES(:publication_id, :auteur_pid)
            ");
            $stmt->bindParam(':publication_id', $id);
            $stmt->bindParam(':auteur_pid', $auth["@pid"]);

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo "❌ Erreur lors de l'insertion de la relation publication-auteur pour l'ID $id : " . $e->getMessage() . "<br>";
            }
        }

        array_push($pid_auteurs, $auth["@pid"]); // Ajouter l'auteur au tableau pour éviter les doublons
    }
}
?>
