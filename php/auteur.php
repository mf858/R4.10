<?php
require_once("db_connection.php");

$publication_id = $_GET['id'];

// Récupérer les infos de la publication
if(isset($publication_id)){
    $query = "SELECT titre FROM groupes.publications WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $publication_id);
    $stmt->execute();
    $publication = $stmt->fetch(PDO::FETCH_ASSOC);
}


// Récupérer les auteurs de cette publication
if(isset($publication_id)){
    $query = "
        SELECT a.pid, a.nom 
        FROM groupes.auteurs a
        INNER JOIN groupes.publication_auteurs pa ON a.pid = pa.auteur_pid
        WHERE pa.publication_id = :id
    ";
}else{
    $query = "
        SELECT a.pid, a.nom 
        FROM groupes.auteurs a
        INNER JOIN groupes.publication_auteurs pa ON a.pid = pa.auteur_pid
    ";
}
$stmt = $pdo->prepare($query);
if(isset($publication_id)){
    $stmt->bindParam(':id', $publication_id);
}
$stmt->execute();
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($auteurs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Auteurs de <?= htmlspecialchars($publication['titre']) ?></title>
</head>
<body>

<h2>Auteurs de : <?= htmlspecialchars($publication['titre']) ?></h2>
<table>
    <tr>
        <thead>     
            <th>Nom</th>
            <th>lien DBLP</th>
            <th>Linkedin</th>
        </thead>
    </tr>
<?php if (count($auteurs) > 0): ?>
    <?php foreach ($auteurs as $auteur): ?>
        <tr>
            <td><?php echo $auteur['nom'] ?></td>
            <td><a href="https://www.dblp.org/pid/<?php echo $auteur['pid'] ?>">Lien</a></td>
            <td><a href="<?php echo( "https://www.linkedin.com/search/results/people/?keywords=".urlencode($row['nom']))?>">linkedin</a></td>

    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>Aucun auteur trouvé pour cette publication.</p>
<?php endif; ?>

<a href="index.php">Retour à la liste des publications</a>

</body>
</html>
