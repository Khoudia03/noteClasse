<?php
require_once dirname(__DIR__)."/models/noteModel.php";

function login() : void {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $email = $_POST['email'];
         $password = $_POST['password'];
        $connexion = connexion($email);
        if(!empty($connexion) && $password === $connexion['motdepass']){
            set_session('connexion',$connexion);
            header("Location: http://localhost:8000/");
            exit;
        }
        
    }
    require_once dirname(__DIR__)."/views/connexion.html.php";
}


function logout() : void {
    destroy_session();
    header("Location: http://localhost:8000/login");
    exit;
}