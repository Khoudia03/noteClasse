<?php
require_once dirname(__DIR__)."/models/noteModel.php";

function note() : void {
    $classes = getAllTable('classes');
    $periodes = getAllTable('periodes');
    $matieres = getAllTable('matieres');
    $annee = getAnneeActive();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $matiere = (int) $_POST['matiere'];
        $periode = (int) $_POST['periode'];
        $classe = (int) $_POST['classe'];
        
        $moyennes = moyenneClasse($matiere,$periode,$classe);
    }
    require_once dirname(__DIR__)."/views/accueil.html.php";
}