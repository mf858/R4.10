<?php
require_once("db_connection.php");

// Vérifier si un auteur est demandé
if (!isset($_GET['pid'])) {
    die("Auteur non spécifié.");
}

$pid = $_GET['pid'];

// Récupérer les infos de l’auteur
$query = "SELECT * FROM groupes.auteurs WHERE pid = :pid";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':pid', $pid);
$stmt->execute();
$auteur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l’auteur existe
if (!$auteur) {
    die("Auteur non trouvé.");
}

// Récupérer les publications associées
$query = "
    SELECT p.id, p.titre, p.annee, p.url 
    FROM groupes.publications p
    INNER JOIN groupes.publication_auteurs pa ON p.id = pa.publication_id
    WHERE pa.auteur_pid = :pid
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':pid', $pid);
$stmt->execute();
$publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Auteur - <?= htmlspecialchars($auteur['nom']) ?></title>
</head>
<body>

<h2>Détails de l'Auteur</h2>
<p><strong>Nom :</strong> <?= htmlspecialchars($auteur['nom']) ?></p>
<p><strong>ID :</strong> <?= htmlspecialchars($auteur['pid']) ?></p>

<h3>Publications de cet Auteur</h3>
<?php if (count($publications) > 0): ?>
    <ul>
        <?php foreach ($publications as $pub): ?>
            <li>
                <a href="<?= htmlspecialchars($pub['url']) ?>" target="_blank"><?= htmlspecialchars($pub['titre']) ?></a> (<?= htmlspecialchars($pub['annee']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune publication trouvée pour cet auteur.</p>
<?php endif; ?>

<a href="index.php">Retour à la liste des publications</a>

</body>
</html>
