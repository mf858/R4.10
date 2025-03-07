<?php
require_once("db_connection.php");

// Vérifier si un ID de publication est fourni
if (!isset($_GET['id'])) {
    die("Publication non spécifiée.");
}

$publication_id = $_GET['id'];

// Récupérer les infos de la publication
$query = "SELECT titre FROM groupes.publications WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $publication_id);
$stmt->execute();
$publication = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si la publication existe
if (!$publication) {
    die("Publication non trouvée.");
}

// Récupérer les auteurs de cette publication
$query = "
    SELECT a.pid, a.nom 
    FROM groupes.auteurs a
    INNER JOIN groupes.publication_auteurs pa ON a.pid = pa.auteur_pid
    WHERE pa.publication_id = :id
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $publication_id);
$stmt->execute();
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auteurs de <?= htmlspecialchars($publication['titre']) ?></title>
</head>
<body>

<h2>Auteurs de : <?= htmlspecialchars($publication['titre']) ?></h2>

<?php if (count($auteurs) > 0): ?>
    <ul>
        <?php foreach ($auteurs as $auteur): ?>
            <li>
                <a href="auteur.php?pid=<?= htmlspecialchars($auteur['pid']) ?>">
                    <?= htmlspecialchars($auteur['nom']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun auteur trouvé pour cette publication.</p>
<?php endif; ?>

<a href="index.php">Retour à la liste des publications</a>

</body>
</html>
