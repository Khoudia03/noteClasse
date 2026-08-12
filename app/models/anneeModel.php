<?php
require_once dirname(__DIR__)."/core/database.php";

function getAnneeActive(): array {
    $pdo = deconnexionDB();
    $sql = "SELECT id, nom FROM anneeScolaires WHERE actif = 1 LIMIT 1";
    return query($pdo, $sql, true);
}