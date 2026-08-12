<?php
require_once dirname(__DIR__)."/models/noteModel.php";
require_once dirname(__DIR__)."/models/anneeModel.php";

function note() : void {
    $classes = getAllTable('classes');
    $periodes = getAllTable('periodes');
    $matieres = getAllTable('matieres');
    $anneeActive = getAnneeActive();
    $moyennes = 0;
    $eleves = [];
    $matiereNonEnseignee = false;
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $matiere = (int) $_POST['matiere'];
        $periode = (int) $_POST['periode'];
        $classe = (int) $_POST['classe'];

        if (!matiereEnseignee($classe, $matiere)) {
            $matiereNonEnseignee = true;
        }else{
            $moyennes = moyenneClasse($matiere,$periode,$classe);
            $eleves = getElevesAvecNotes($classe, $matiere, $periode);
        }
        
    }
    require_once dirname(__DIR__)."/views/accueil.html.php";
}