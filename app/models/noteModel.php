<?php
require_once dirname(__DIR__)."/core/database.php";


function moyenneClasse(int $matiereId, int $periodeId, int $classeId, int $actif = 1): float {
    $pdo = deconnexionDB();

    $sql = "SELECT ROUND(COALESCE(AVG(
                (COALESCE(ev.devoir1,0) + COALESCE(ev.devoir2,0) + 2*COALESCE(ev.composition,0)) / 4
            ), 0), 2) AS moyenne
            FROM inscriptions i
            INNER JOIN anneeScolaires a ON a.id = i.annee_id
            LEFT JOIN evaluations ev
                ON ev.inscription_id = i.id
                AND ev.matiere_id = :idMatiere
                AND ev.periode_id = :idPeriode
            WHERE i.classe_id = :idClasse
              AND a.actif = :actif";

    $result = executeQuery($pdo, $sql, [
        'idMatiere' => $matiereId,
        'idPeriode' => $periodeId,
        'idClasse'  => $classeId,
        'actif'     => $actif,
    ]);

    return (float) ($result['moyenne'] ?? 0);
}

function connexion(string $email) : array {
    $pdo = deconnexionDB();

    $sql = "SELECT u.*,r.* from utilisateurs u
    inner join roles r on u.role_id=r.id
    WHERE
    email=:email";
    $connexion = executeQuery($pdo,$sql,[
        'email'=>$email
    ]);
    return $connexion;
    
}