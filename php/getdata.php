<?php
require_once("db_connection.php"); // Inclusion du fichier de connexion à la base PostgreSQL

/**
 * Fonction pour obtenir le domaine d'une publication en analysant son titre.
 */
function get_domain($text){
    // Charger le fichier JSON contenant les mots-clés associés aux domaines
    $mots_json = file_get_contents("../json/mots.json");
    $mots = json_decode($mots_json,true); // Décoder le JSON en tableau PHP
    
    $domains = $mots["domains"]; // Liste des domaines
    foreach($domains as $domain){ // Parcourir chaque domaine
        $liste_mots = $domain["keywords"]; // Liste des mots-clés pour ce domaine
        foreach($liste_mots as $liste){ // Vérifier si un mot-clé est présent dans le texte
            if(str_contains($text,$liste)){
                return $domain["name"]; // Retourner le nom du domaine correspondant
            }
        }
    }
    return "Aucun domaine trouvé"; // Si aucun mot-clé ne correspond
}

// Requête SQL pour récupérer les publications
$query = "
    SELECT p.id, p.score, p.titre, p.lieu, p.annee, p.acces, p.format, p.url, p.domaine
    FROM groupes.publications p
";

$stmt = $pdo->prepare($query); // Préparation de la requête SQL
$stmt->execute(); // Exécution de la requête
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupération des résultats sous forme de tableau associatif
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications avec Auteurs</title>
    <link rel="stylesheet" href="../style.css"> <!-- Inclusion du fichier CSS -->
</head>
<body>

<h2>Liste des Publications</h2>

<!-- Champs de recherche -->
<input type="text" class="search" id="searchTitre" placeholder="Rechercher un titre..." onkeyup="searchTable()">
<input type="text" class="search" id="searchDomaine" placeholder="Rechercher un domaine..." onkeyup="searchTable()">

<!-- Table d'affichage des publications -->
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
/**
 * Fonction de recherche dynamique dans la table
 */
function searchTable() {
    let inputTitre = document.getElementById("searchTitre").value.toLowerCase(); // Récupération du texte de recherche pour le titre
    let inputDomaine = document.getElementById("searchDomaine").value.toLowerCase(); // Récupération du texte de recherche pour le domaine

    let rows = document.querySelectorAll("#publicationsTable tbody tr"); // Sélection des lignes du tableau

    rows.forEach(row => {
        let titre = row.cells[2].textContent.toLowerCase(); // Récupération du titre
        let domaine = row.cells[3].textContent.toLowerCase(); // Récupération du domaine

        // Afficher la ligne si elle correspond aux critères de recherche
        row.style.display = (titre.includes(inputTitre) && domaine.includes(inputDomaine)) ? "" : "none";
    });
}
</script>

</body>
</html>
