<?php
require_once("db_connection.php"); // Inclusion du fichier de connexion à la base PostgreSQL

// Récupération de l'ID de la publication depuis l'URL (ex: auteur.php?id=123)
$publication_id = $_GET['id'];

// Vérifier si un ID est bien fourni dans l'URL
if(isset($publication_id)){
    // Requête pour récupérer le titre de la publication correspondante
    $query = "SELECT titre FROM groupes.publications WHERE id = :id";
    $stmt = $pdo->prepare($query); // Préparation de la requête SQL
    $stmt->bindParam(':id', $publication_id); // Liaison du paramètre ID
    $stmt->execute(); // Exécution de la requête
    $publication = $stmt->fetch(PDO::FETCH_ASSOC); // Récupération du titre
}

// Récupération des auteurs de cette publication
if(isset($publication_id)){
    // Si un ID de publication est fourni, récupérer uniquement les auteurs de cette publication
    $query = "
        SELECT a.pid, a.nom, a.affiliation
        FROM groupes.auteurs a
        INNER JOIN groupes.publication_auteurs pa ON a.pid = pa.auteur_pid
        WHERE pa.publication_id = :id
    ";
}else{
    // Si aucun ID fourni, récupérer tous les auteurs de toutes les publications
    $query = "
        SELECT a.pid, a.nom, a.affiliation
        FROM groupes.auteurs a
        INNER JOIN groupes.publication_auteurs pa ON a.pid = pa.auteur_pid
    ";
}

// Préparation de la requête SQL
$stmt = $pdo->prepare($query);

// Lier l'ID de la publication si un ID a été fourni
if(isset($publication_id)){
    $stmt->bindParam(':id', $publication_id);
}

// Exécuter la requête
$stmt->execute();

// Récupérer les résultats sous forme de tableau associatif
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// print_r($auteurs); // Décommenter cette ligne pour tester l'affichage des auteurs en mode debug
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Auteurs de <?= htmlspecialchars($publication['titre'] ?? "Publication Inconnue") ?></title>
</head>
<body>

<h2>Auteurs de : <?= htmlspecialchars($publication['titre'] ?? "Publication Inconnue") ?></h2>

<!-- Table pour afficher les auteurs -->
<table>
    <thead>     
        <tr>
            <th>Nom</th>
            <th>Affiliation</th>
            <th>Lien DBLP</th>
            <th>LinkedIn</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($auteurs) > 0): ?>
            <?php foreach ($auteurs as $auteur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($auteur['nom']); ?></td>
                    <td><?php echo htmlspecialchars($auteur['affiliation']); ?></td>
                    <td><a href="https://www.dblp.org/pid/<?php echo htmlspecialchars($auteur['pid']); ?>" target="_blank">Lien DBLP</a></td>
                    <td><a href="https://www.linkedin.com/search/results/people/?keywords=<?= urlencode($auteur['nom']); ?>" target="_blank">LinkedIn</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucun auteur trouvé pour cette publication.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Lien retour -->
<a href="getdata.php">Retour à la liste des publications</a>

</body>
</html>
