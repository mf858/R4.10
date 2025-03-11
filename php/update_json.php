<?php


// Tableau de correspondance Venue → Domaine
$venue_to_domain = [
    "Comput. Phys. Commun." => "Physique Computationnelle",
    "Frontiers Artif. Intell." => "Intelligence Artificielle",
    "Nat." => "Sciences Générales",
    "Comput. Softw. Big Sci." => "Big Data & Informatique Scientifique",
    "CoRR" => "Prépublications en Informatique",
    "NeuroImage" => "Neurosciences & Imagerie Médicale",
    "VLSI Technology and Circuits" => "Conception de Circuits (VLSI)",
    "SC" => "Supercomputing (HPC)",
    "Comput. Appl. Math." => "Mathématiques Appliquées",
    "BMC Medical Informatics Decis. Mak." => "Informatique Médicale",
    "npj Digit. Medicine" => "Médecine Numérique",
    "WINCOM" => "Réseaux Sans Fil",
    "Sensors" => "Capteurs et Systèmes Intelligents",
    "Appl. Math. Lett." => "Mathématiques Appliquées",
    "CINAIC" => "Intelligence Artificielle & Éducation",
    "Comput. Sci. Rev." => "Revue Générale en Informatique",
    "Future Internet" => "Réseaux & Internet du Futur",
    "WITCOM" => "Communications & Réseaux",
    "BROADNETS" => "Réseaux de Communication",
    "Int. J. Bifurc. Chaos" => "Mathématiques - Théorie du Chaos",
    "CIARP" => "Reconnaissance des Formes & Vision",
    "WISATS" => "Satellites & Télécommunications",
    "PSATS" => "Télécommunications par Satellite",
    "Axioms" => "Mathématiques & Logique",
    "Database J. Biol. Databases Curation" => "Bases de Données Biologiques",
    "BMC Bioinform." => "Bioinformatique",
    "Int. J. Medical Informatics" => "Informatique Médicale",
    "Nucleic Acids Res." => "Biologie Moléculaire",
    "IEEE Access" => "Revue Multidisciplinaire",
    "J. Vis. Commun. Image Represent." => "Vision par Ordinateur & Compression",
    "SMACD" => "Conception de Circuits Intégrés",
    "UCET" => "Ingénierie & Technologie",
    "J. Parallel Distributed Comput." => "Calcul Parallèle & Distribué",
    "CIT/IUCC/DASC/PICom" => "Informatique & Systèmes Distribués",
    "IAS" => "Informatique & Systèmes Intelligents",
    "IDT" => "Technologies de l’Information",
    "Int. J. Inf. Manag." => "Gestion de l’Information",
    "Telecommun. Syst." => "Télécommunications"
];


// Charger le fichier JSON
$json_file = '../../publ.json';
$json_data = file_get_contents($json_file);

$decoded_data = json_decode($json_data, true);
$data = $decoded_data["result"]["hits"]["hit"];

$pid_auteurs = [];

for($i = 0; $i < sizeof($data); $i++){
    $lieu = $data[$i]["info"]["venue"];
    $data[$i]["info"]["domaines"] =$venue_to_domain[$lieu] ?? "Domaine inconnu";

}

// Convertir en JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
