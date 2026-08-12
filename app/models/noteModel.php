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

function matiereEnseignee(int $classeId, int $matiereId): bool {
    $pdo = deconnexionDB();
    $sql = "SELECT id FROM matiere_classes WHERE classe_id = :idClasse AND matiere_id = :idMatiere";
 
    $ligne = executeQuery($pdo, $sql, [
        'idClasse'  => $classeId,
        'idMatiere' => $matiereId,
    ]);
 
    return !empty($ligne);
}

function getElevesAvecNotes(int $classeId, int $matiereId, int $periodeId, int $actif = 1): array {
    $pdo = deconnexionDB();
 
    $sql = "SELECT
                e.id AS idEleve,
                i.id AS idInscription,
                e.matricule,
                e.nom,
                e.prenom,
                COALESCE(ev.devoir1, 0) AS devoir1,
                COALESCE(ev.devoir2, 0) AS devoir2,
                COALESCE(ev.composition, 0) AS composition,
                ROUND(
                    (COALESCE(ev.devoir1,0) + COALESCE(ev.devoir2,0) + 2*COALESCE(ev.composition,0)) / 4,
                    2
                ) AS moyenne,
                CASE
                    WHEN ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) < 10
                        THEN 'Insuffisant'
                    WHEN ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) BETWEEN 10 AND 12
                        THEN 'Passable'
                    WHEN ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) > 12
                     AND ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) <= 14
                        THEN 'Assez bien'
                    WHEN ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) > 14
                     AND ROUND((COALESCE(ev.devoir1,0)+COALESCE(ev.devoir2,0)+2*COALESCE(ev.composition,0))/4,2) <= 16
                        THEN 'Bien'
                    ELSE 'Très bien'
                END AS appreciation
            FROM inscriptions i
            INNER JOIN eleves e ON e.id = i.eleve_id
            INNER JOIN anneeScolaires a ON a.id = i.annee_id
            LEFT JOIN evaluations ev
                ON ev.inscription_id = i.id
                AND ev.matiere_id = :idMatiere
                AND ev.periode_id = :idPeriode
            WHERE i.classe_id = :idClasse
              AND a.actif = :actif
            ORDER BY e.nom, e.prenom";
 
    return executeQuery($pdo, $sql, [
        'idMatiere' => $matiereId,
        'idPeriode' => $periodeId,
        'idClasse'  => $classeId,
        'actif'     => $actif,
    ], false);
}