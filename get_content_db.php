<?php
require_once("db_connection.php");

$stmt = $pdo->prepare("select * from groupes.publications");

$stmt->execute();
$result = $stmt->fetchall(PDO::FETCH_ASSOC);

echo("<pre>");
print_r($result);
echo ("</pre>");
?>