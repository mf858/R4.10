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

// Requête pour récupérer les publications
$query = "
    SELECT p.id, p.score, p.titre, p.lieu, p.annee, p.acces, p.format, p.url, p.domaine
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

<input type="text" class="search" id="searchTitre" placeholder="Rechercher un titre..." onkeyup="searchTable()">
<input type="text" class="search" id="searchDomaine" placeholder="Rechercher un domaine..." onkeyup="searchTable()">


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
                <td><?= htmlspecialchars($row['domaine']) ?></td>
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
    let inputTitre = document.getElementById("searchTitre").value.toLowerCase();
    let inputDomaine = document.getElementById("searchDomaine").value.toLowerCase();

    let rows = document.querySelectorAll("#publicationsTable tbody tr");

    rows.forEach(row => {
        let titre = row.cells[2].textContent.toLowerCase();
        let domaine = row.cells[3].textContent.toLowerCase();

        // Afficher seulement si les deux conditions sont satisfaites
        row.style.display = (titre.includes(inputTitre) && domaine.includes(inputDomaine)) ? "" : "none";
    });
}
</script>

</body>
</html>