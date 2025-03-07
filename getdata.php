<?php
require_once("db_connection.php");

// Requête pour récupérer les publications avec leurs auteurs
$query = "
    SELECT p.id, p.score, p.titre, p.lieu, p.annee, p.acces, p.format, p.url, 
           STRING_AGG(a.nom || '|' || a.pid, ', ') AS auteurs
    FROM groupes.publications p
    LEFT JOIN groupes.publication_auteurs pa ON p.id = pa.publication_id
    LEFT JOIN groupes.auteurs a ON pa.auteur_pid = a.pid
    GROUP BY p.id, p.score, p.titre, p.lieu, p.annee, p.acces, p.format, p.url
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
</head>
<body>

<h2>Liste des Publications</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Score</th>
            <th>Titre</th>
            <th>Lieu</th>
            <th>Année</th>
            <th>Accès</th>
            <th>Format</th>
            <th>Auteurs</th>
            <th>Lien</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): 
            $auteurs = explode(", ", $row['auteurs'] ?? 'Aucun auteur');
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['score']) ?></td>
                <td><?= htmlspecialchars($row['titre']) ?></td>
                <td><?= htmlspecialchars($row['lieu']) ?></td>
                <td><?= htmlspecialchars($row['annee']) ?></td>
                <td><?= htmlspecialchars($row['acces']) ?></td>
                <td><?= htmlspecialchars($row['format']) ?></td>
                <td>
                    <?php foreach ($auteurs as $auteur): 
                        if ($auteur !== 'Aucun auteur') {
                            list($nom, $pid) = explode('|', $auteur);
                            echo "<a href='auteur.php?pid=" . htmlspecialchars($pid) . "'>" . htmlspecialchars($nom) . "</a><br>";
                        } else {
                            echo "Aucun auteur";
                        }
                    endforeach; ?>
                </td>
                <td><a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
