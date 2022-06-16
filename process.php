<?php

/**
 * Gestion des sessions pour faciliter le refresh et redirection vers l'accueil
 */

 session_start();

 // Par défaut, le mode édition n'est pas activé;
 $update = false;

 // Par défaut le contenu des variables

 $name = "";
 $lastname = "";
 $email = "";
 $phone = "";
 $id = 0;

/**
 * On se connecte à la BDD 
 * localhost + Nom de l'utilisateur + mdp + nom de la bdd
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
    VALUES ('$name','$lastname','$email','$phone')") or die(mysqli_error($mysqli));

    $_SESSION['message'] = "Contact enregistré";
    $_SESSION['msg_type'] = "success";

    header('Location:index.php');
 }

 /**
  * Effacer une ligne de la BDD
  * On ecoute le bouton, on stoke
  */
  if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $mysqli->query("DELETE FROM data WHERE id=$id") or die($mysqli->error());

    $_SESSION['message'] = "Contact effacé";
    $_SESSION['msg_type'] = "danger";

    header('Location:index.php');
  }

  /**
  * Edit de la BDD
  */
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = $mysqli->query("SELECT * FROM data WHERE id=$id") or die($mysqli->error());
    if ($result->num_rows) {
        $row = $result->fetch_array();
        $name = $row['prenom'];
        $lastname = $row['nom'];
        $email = $row['email'];
        $phone = $row['portable'];
    }
  }

  /**
   * Envoyer vers la BDD la mise à jour du form
   */
  if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['prenom'];
    $lastname = $_POST['nom'];
    $email = $_POST['email'];
    $phone = $_POST['portable'];

    $result = $mysqli->query("UPDATE data SET prenom='$name', nom='$lastname', 
    email='$email', portable='$phone' WHERE id=$id") or die($mysqli->error());

    $_SESSION['message'] = "Contact mis à jour !";
    $_SESSION['msg_type'] = "warning";

    // Redirection vers la page index.php
    header('Location:index.php'); 
  }