<?php



/**
 * Gestion des sessions pour faciliter le refresh et redirection vers l'accueil
 */

 session_start();

 echo $_SERVER['REMOTE_ADDR'];

foreach ($_SERVER as $key => $value) {
    echo $key.' '.$value.'<br>';
}

if ($_SERVER['REMOTE_ADDR']=="127.0.0.1" || $_SERVER['REMOTE_ADDR']=="::1") {
    try
        {   
            $bdd = new PDO("mysql:host=localhost;dbname=annuaire", "root", "");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e)    {die($erreur_sql='Erreur connect bd: '.$e->getMessage());}
}
else {
    try
        {   
            $bdd = new PDO("mysql:host=[sql hote];dbname=[nom de la base]", "[utilisateur]", "[mot de passe]");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e)    {die($erreur_sql='Erreur connect bd: '.$e->getMessage());}
}

try {
  $sql="SELECT * FROM data;";
  $stmt = $bdd->prepare($sql);
  $stmt->execute();
  } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
  // $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
  // print_r($results);

  echo '<hr>';

  while($results=$stmt->fetch(PDO::FETCH_ASSOC)) {
      print_r($results);
      ?>
      <input type="tel" name="portable" class="form-control" value="<?php echo $results['phone']; ?>" placeholder="Renseignez votre numéro de portable">

  <?php }
  

/**
 * On se connecte à la BDD 
 * localhost + Nom de l'utilisateur + mdp + nom de la bdd
 */


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