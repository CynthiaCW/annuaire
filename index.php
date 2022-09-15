<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Annuaire</title>
    
</head>
<body>

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

// $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($results);

echo '<hr>';

$update = false;

if (!empty($_POST)) {
    print_r($_POST);

    if (isset($_POST['update'])) { 
        
        try {
            $sql = "UPDATE data SET nom=?, prenom=?, email=?, portable=? WHERE id = ?";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(array(
                strip_tags($_POST['nom']),
                strip_tags($_POST['prenom']),
                strip_tags($_POST['email']),
                strip_tags($_POST['portable']),
                strip_tags($_POST['id'])
            ));
            $stmt->execute(array(
                strip_tags($_POST['id'])
            ));
        } catch(PDOException $e) {echo 'Erreur: '.$sql . "<br>" . $e->getMessage();$erreur=$sql;}
    } 
    else if (isset($_POST['delete'])) {  
        try {
            $sql = "DELETE FROM data WHERE id = ?";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(array(
                strip_tags($_POST['id'])
            ));
        } catch(PDOException $e) {echo 'Erreur: '.$sql . "<br>" . $e->getMessage();$erreur=$sql;}
    }
    else if (isset($_POST['insert'])) { 
        
        if(filter_var(strip_tags($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            echo "L'email est correct";
            echo '<br>';
             // Insérer nouveau 
             try {
                $sql = "INSERT INTO data SET nom=?, prenom=?, email=?, portable=?";
                $stmt = $bdd->prepare($sql);
                $stmt->execute(array(
                    strip_tags($_POST['nom']),
                    strip_tags($_POST['prenom']),
                    strip_tags($_POST['email']),
                    strip_tags($_POST['portable'])
                ));

                $last_id = $bdd->lastInsertId();
                echo 'Le dernier id inséré est > '.$last_id;

            } catch(PDOException $e) {echo 'Erreur: '.$sql . "<br>" . $e->getMessage();$erreur=$sql;}
          }
          else
          { 
            // Ne pas insérer
            echo "L'email n'est pas correct, nous n'avons pas pu vous insérer";
          } 
    } 
}

try {
    $sql="SELECT * FROM data;";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
} catch (Exception $e) {
    print "Erreur ! " . $e->getMessage() . "<br/>";
}

?>

    <div class="row justify-content-center shadow p-3 mb-5 bg-white rounded">

        <table class="table">
            <thead>
                <th>Id</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Portable</th>
                <th colspan=2>Action</th>
            </thead>
        
        <?php
            while ($results=$stmt->fetch(PDO::FETCH_ASSOC)) :
            // print_r($results);
        ?>
            <tr>
                <td><?= $results['id']; ?></td>
                <td><?= $results['prenom']; ?></td>
                <td><?= $results['nom']; ?></td>
                <td><?= $results['email']; ?></td>
                <td><?= $results['portable']; ?></td>
                
                <td>
                    <form methods="POST">
                        <?php if(isset($_POST['edit'])) {?>
                            <input type="hidden" name="update" value=<?php echo $results['id']; ?>>
                            <button type="submit" name="update" class="btn btn-info"> Mettre à jour</button> 
                        <?php } else if(isset($_POST['delete'])) {?>}
                            <input type="hidden" name="delete" value=<?php echo $results['id']; ?>>
                            <button type="submit" name="delete" class="btn btn-info"> Supprimer</button>
                        <?php }?>    
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>       
    </div>

<?php if (isset($_GET['edit'])) {
    //Selectionner tout de la table data
    try {
        $sql="SELECT * FROM data WHERE id=?;";
        $stmt = $bdd->prepare($sql);
        $stmt->execute(array($_GET['edit']));
    } catch (Exception $e) {
        print "Erreur ! " . $e->getMessage() . "<br/>";
    } 
    $results=$stmt->fetch(PDO::FETCH_ASSOC);
    print_r($results);

} else if($results['prenom']== true || $results['nom']== true || $results['email']== true || $results['portable'] == true) {
    $results=$stmt->fetch(PDO::FETCH_ASSOC);
    print_r($results);
}
?>
 
<div class="row justify-content-center shadow p-3 mb-5 bg-white rounded">
                <form method="POST">
                    <h2>Formulaire</h2>
                    <div class="form-group">
                        <label for="">Prénom</label>
                        <input type="text" name="prenom" class="form-control" placeholder="Renseignez votre prénom" value=<?php echo $results['prenom'] ?>>
                    </div>
                    <div class="form-group">
                        <label for="">Nom</label>
                        <input type="text" name="nom" class="form-control" placeholder="Renseignez votre nom" value=<?php echo $results['nom'] ?>>
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Renseignez votre adresse mail" value=<?php echo $results['email'] ?>>
                    </div>
                    <div class="form-group">
                        <label for="">Portable</label>
                        <input type="tel" name="portable" class="form-control" placeholder="Renseignez votre numéro de portable" value=<?php echo $results['portable'] ?>>
                    </div>
                    <div class="form-group">
                        <!-- Transformation du bouton mise à jour -->
                            <button type="submit" name="insert" class="btn btn-info"> Insérer</button>
                            <button type="submit" name="update" class="btn btn-info"> Mettre à jour</button>
                    </div>
                </form> 
            </div>
</body>
</html>