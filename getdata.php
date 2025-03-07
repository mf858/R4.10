<?php
require_once("db_connection.php");

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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #ffe6f0; /* Rose pâle */
        }
        h2 {
            text-align: center;
            color: #E91E63; /* Rose vif */
        }
        #search {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #E91E63;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }
        #search:focus {
            border-color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #E91E63; /* Rose vif */
            color: white;
            cursor: pointer;
        }
        th:hover {
            background: #D81B60;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #C8E6C9; /* Vert clair */
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #388E3C;
        }
    </style>
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
                <td><?= htmlspecialchars($row['lieu']) ?></td>
                <td><?= htmlspecialchars($row['annee']) ?></td>
                <td><?= htmlspecialchars($row['acces']) ?></td>
                <td><?= htmlspecialchars($row['format']) ?></td>
                <td>
                    <a href="auteurs.php?id=<?= htmlspecialchars($row['id']) ?>">Voir les auteurs</a>
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
