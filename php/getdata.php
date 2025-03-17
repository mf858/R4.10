<?php
require_once("db_connection.php");

function get_domain($text){
    $mots_json = file_get_contents("../json/mots.json");
    $mots = json_decode($mots_json,true); 
    $domains = $mots["domains"];
    foreach($domains as $domain){
        $liste_mots = $domain["keywords"];
        foreach($liste_mots as $liste){
            if(str_contains($text,$liste)){
                return $domain["name"];
            }
        }
    }
    return "Aucun domaine trouvé";
}

function requete_openalex(){
    //prendre les doi du json publ

    $file = file_get_contents("../json/publ.json");
    $decoded_data = json_decode($file, true);
    $data = $decoded_data["result"]["hits"]["hit"];

    //print_r("<pre>".$data."</pre>");
    for($i = 0; $i < sizeof($data); $i++){
        $doi  = $data[$i]["info"]["doi"];
        print_r($data[$i]["info"]["doi"]); 
    
        $encoded_doi = urlencode($doi);
        print_r($doi."|".$encoded_doi);
    
        //envoie de la requete à l'api
        $url = "https://api.openalex.org/works/https://doi.org/" . $encoded_doi;
        print_r($url);
        $response = file_get_contents($url);
    
        //print_r($response);
        /*
        if ($response !== FALSE) {
            $datalex = json_decode($response, true);
            if (isset($datalex['concepts'])) {
                foreach ($datalex['concepts'] as $concept) {
                    echo "Concept: " . $concept['display_name'] . " (score: " . $concept['score'] . ")" . "\n";
                }
            }
        }*/
        echo("</br>");
        //envoie du resultat à la bdd
              
    }
}
    

requete_openalex();
// Requête pour récupérer les publications
$query = "
    SELECT p.id, p.score, p.titre, p.lieu, p.annee, p.acces, p.format, p.url
    FROM groupes.publications p
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications avec Auteurs</title>
    <link rel="stylesheet" href="../style.css">
    
</head>
<body>

<h2>Liste des Publications</h2>

<input type="text" id="search" placeholder="Rechercher un titre..." onkeyup="searchTable()">

<table id="publicationsTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Score</th>
            <th>Titre</th>
            <th>Domaine</th>
            <th>Lieu</th>
            <th>Année</th>
            <th>Accès</th>
            <th>Format</th>
            <th>Auteurs</th>
            <th>Lien</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['score']) ?></td>
                <td><?= htmlspecialchars($row['titre']) ?></td>
                <td><?= htmlspecialchars(get_domain($row['titre'])) ?></td>
                <td><?= htmlspecialchars($row['lieu']) ?></td>
                <td><?= htmlspecialchars($row['annee']) ?></td>
                <td><?= htmlspecialchars($row['acces']) ?></td>
                <td><?= htmlspecialchars($row['format']) ?></td>
                <td>
                    <a href="auteur.php?id=<?= htmlspecialchars($row['id']) ?>">Voir les auteurs</a>
                </td>
                <td><a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function searchTable() {
        let input = document.getElementById("search").value.toLowerCase();
        let rows = document.querySelectorAll("#publicationsTable tbody tr");

        rows.forEach(row => {
            let titre = row.cells[2].textContent.toLowerCase();
            row.style.display = titre.includes(input) ? "" : "none";
        });
    }
</script>

</body>
</html>