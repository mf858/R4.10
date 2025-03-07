<?php
require_once("db_connection.php");

    $file = file_get_contents("publ.json");
    $decoded_data = json_decode($file, true);
    $data = $decoded_data["result"]["hits"]["hit"];

    $pid_auteurs = [];

    for($i = 0; $i < sizeof($data); $i++){
        //echo "<pre>";
        //print_r($data[$i]["info"]["title"]);
        //echo "<pre>";
        $id = $data[$i]["@id"];
        $score = $data[$i]["@score"];
        $titre = $data[$i]["info"]["title"];
        $lieu = $data[$i]["info"]["venue"];
        $annee = $data[$i]["info"]["year"];
        $format = $data[$i]["info"]["type"];
        $acces = $data[$i]["info"]["access"];
        $url = $data[$i]["info"]["url"];
        $auteurs = $data[$i]["info"]["authors"]["author"];


        $stmt = $pdo->prepare("insert into groupes.publications(id, score, titre, lieu, annee, acces, format, url)
                            values(:id, :score, :titre, :lieu, :annee, :acces, :format, :url)");
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':score',$score);
        $stmt->bindParam(':titre',$titre);
        $stmt->bindParam(':lieu',$lieu);
        $stmt->bindParam(':annee',$annee);
        $stmt->bindParam(':format',$format);
        $stmt->bindParam(':acces',$acces);
        $stmt->bindParam(':url',$url);

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
            }
            array_push($pid_auteurs,$auth["@pid"]);
        }
    }



?>