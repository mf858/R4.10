<?php
    require_once("db_connection.php");

    $query = "select * from groupes.auteurs";

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
            <th>PID</th>
            <th>Nom</th>
            <th>Lien</th>
            <th>Linkedin</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['pid']) ?></td>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><a href="<?= htmlspecialchars("https://dblp.org/pid/".$row['pid']) ?>" target="_blank">Voir</a></td>
                <td><a href="<?php echo( "https://www.linkedin.com/search/results/people/?keywords=".urlencode($row['nom']))?></a>></a></td>
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