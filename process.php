<?php



/**
 * Gestion des sessions pour faciliter le refresh et redirection vers l'accueil
 */

session_start();

if ($_SERVER['REMOTE_ADDR']=="127.0.0.1" || $_SERVER['REMOTE_ADDR']=="::1") {
    try {
        $bdd = new PDO("mysql:host=localhost;dbname=annuaire", "root", "");
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(Exception $e) {
        die($erreur_sql='Erreur connect bd: '.$e->getMessage());
    }
} else {
    try {
        $bdd = new PDO("mysql:host=[sql hote];dbname=[nom de la base]", "[utilisateur]", "[mot de passe]");
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(Exception $e) {
        die($erreur_sql='Erreur connect bd: '.$e->getMessage());
    }
}

try {
    $sql="SELECT * FROM data;";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
} catch (Exception $e) {
    print "Erreur ! " . $e->getMessage() . "<br/>";
}
// $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($results);

echo '<hr>';

$update = false;

if (isset($_POST['update'])) { print_r($_POST);
    try {
    $sql = "UPDATE data SET nom=?,prenom=?, email=?, portable=? WHERE id=?";
      $stmt = $bdd->prepare($sql);
      $stmt->execute(
        array(
          strip_tags($_POST['nom']),
          strip_tags($_POST['prenom']),
          strip_tags($_POST['email']),
          strip_tags($_POST['portable']),
          strip_tags($_POST['id'])
        ));
    }
  catch(PDOException $e) {echo 'Erreur: '.$sql . "<br>" . $e->getMessage();$erreur=$sql;}
}



while ($results=$stmt->fetch(PDO::FETCH_ASSOC)) {
    // print_r($results);
    ?>



       <div class="row justify-content-center shadow p-3 mb-5 bg-white rounded">
                
                <form action="process.php" method="POST">
                    <h2>Formulaire</h2>
                    <input type="hidden" name="id" value="<?php echo $results['id']; ?>">
                    <div class="form-group">
                        <label for="">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?php echo $results['prenom']; ?>" placeholder="Renseignez votre prénom">
                    </div>
                    <div class="form-group">
                        <label for="">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?php echo $results['nom']; ?>" placeholder="Renseignez votre nom">
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $results['email']; ?>" placeholder="Renseignez votre adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="">Portable</label>
                        <input type="tel" name="portable" class="form-control" value="<?php echo $results['portable']; ?>" placeholder="Renseignez votre numéro de portable">
                    </div>
                    <div class="form-group">
                        <!-- Transformation du bouton mise à jour -->
                        <?php if($update == true) : ?>
                            <button type="submit" name="update" class="btn btn-info"> Mettre à jour</button> 
                        <?php else : ?>
                            <button type="submit" class="btn btn-primary" name="save">Enregistrer</button>
                        <?php endif; ?>
                    </div>
                </form> 
                
            </div>

  <?php }


?>