<?php
require_once("db_connection.php");

    $file = file_get_contents("../json/publ.json");
    $decoded_data = json_decode($file, true);
    $data = $decoded_data["result"]["hits"]["hit"];

    $pid_auteurs = [];

    for($i = 0; $i < sizeof($data); $i++){
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

        print_r($doi);


        //recherche API
        $encoded_doi = urlencode($doi);
        print_r($encoded_doi);
        $url = "https://api.openalex.org/works/doi:" . $encoded_doi;
        print_r($url);  
        $response = file_get_contents($url);
    
        //print_r($response);
        if ($response !== FALSE) {
            $datalex = json_decode($response, true);
            if (isset($datalex['concepts'])) {
                $domaine = $datalex["concepts"]["display_name"];
            }
        }   

        //insert publications
        $stmt = $pdo->prepare("insert into groupes.publications(id, score, titre, lieu, annee, acces, format, url, domaine)
                            values(:id, :score, :titre, :lieu, :annee, :acces, :format, :url, :domaine)");
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':score',$score);
        $stmt->bindParam(':titre',$titre);
        $stmt->bindParam(':lieu',$lieu);
        $stmt->bindParam(':annee',$annee);
        $stmt->bindParam(':format',$format);
        $stmt->bindParam(':acces',$acces);
        $stmt->bindParam(':url',$adresse);
        $stmt->bindParam(':domaine',$domaine);

        try {
            $stmt->execute();
            echo "Données insérées pour l'ID : $id<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion de l'ID $id : " . $e->getMessage() . "<br>";
        }

        foreach($auteurs as $auth){
            if(in_array($auth["@pid"],$pid_auteurs) == false){
                $stmt = $pdo->prepare("insert into groupes.auteurs(pid, nom)
                                values(:pid, :nom)");
                $stmt->bindParam(':pid',$auth["@pid"]);
                $stmt->bindParam(':nom',$auth["text"]);
    
                try {
                    $stmt->execute();
    
                } catch (PDOException $e) {
                    echo "Erreur lors de l'insertion de l'ID $id : " . $e->getMessage() . "<br>";
                }
                

                //insert publications auteurs
                $stmt = $pdo->prepare("insert into groupes.publication_auteurs(publication_id, auteur_pid)
                values(:publication_id, :auteur_pid)");
                $stmt->bindParam(':publication_id',$id);
                $stmt->bindParam(':auteur_pid',$auth["@pid"]);
                
                try {
                    $stmt->execute();
                    
                } catch (PDOException $e) {
                    echo "Erreur lors de l'insertion de l'ID $id : " . $e->getMessage() . "<br>";
                }
            }
                array_push($pid_auteurs,$auth["@pid"]);
        }
    }

?>