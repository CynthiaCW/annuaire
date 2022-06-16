<?php

/**
 * On se connecte à la BDD 
 * Nom de l'utilisateur + mdp
 */

 $mysqli = new mysqli('localhost', 'annuaire', '435326co777', 'annuaire') 
 or die(mysqli_error($mysqli));

/**
 * Ecoute le bouton enregistrer et va chercher les valeurs des input
 * On enregistre tout les input de la BDD
 */
 if (isset($_POST ['save'])) {
    $name = $_POST['prenom'];
    $lastname = $_POST['nom'];
    $email = $_POST['email'];
    $phone = $_POST['portable'];

    $mysqli->query("INSERT INTO data (prenom, nom, email, portable) 
    VALUES ('$name','$lastname','$email','$phone')") 
    or die(mysqli_error($mysqli));
 }