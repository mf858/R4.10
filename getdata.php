<!-- <?php
$data = file_get_contents("https://dblp.org/search/publ/api?q=a&h=100&format=json");
    
file_put_contents("data.json",$data);
?>
<pre>
    <?php echo $data ?>
</pre> -->

<?php
require_once("db_connection.php");

$stmt = $pdo->prepare("SELECT * FROM groupes.publications");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications</title>
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
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Score</th>
            <th onclick="sortTable(2)">Titre</th>
            <th onclick="sortTable(3)">Lieu</th>
            <th onclick="sortTable(4)">Année</th>
            <th onclick="sortTable(5)">Accès</th>
            <th onclick="sortTable(6)">Format</th>
            <th onclick="sortTable(7)">Lien</th>
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
                <td><a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">Voir</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Fonction de recherche
    function searchTable() {
        let input = document.getElementById("search").value.toLowerCase();
        let rows = document.querySelectorAll("#publicationsTable tbody tr");

        rows.forEach(row => {
            let titre = row.cells[2].textContent.toLowerCase();
            row.style.display = titre.includes(input) ? "" : "none";
        });
    }

    // Fonction de tri
    function sortTable(n) {
        let table = document.getElementById("publicationsTable");
        let rows = Array.from(table.rows).slice(1);
        let ascending = table.getAttribute("data-sort") !== "asc";

        rows.sort((rowA, rowB) => {
            let cellA = rowA.cells[n].textContent.trim();
            let cellB = rowB.cells[n].textContent.trim();
            return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        rows.forEach(row => table.appendChild(row));
        table.setAttribute("data-sort", ascending ? "asc" : "desc");
    }
</script>

</body>
</html>
