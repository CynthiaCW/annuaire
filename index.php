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
    <?php require_once 'process.php'; ?>

    <?php if (isset($_SESSION ['message'])) : ?>
        <div class="alert bg-<?= $_SESSION['msg_type'] ?> text-white font-weight-bold"> <!-- alert- : rend le css dynamique -->
            <?php
                echo $_SESSION['message'];
            ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <br>
            <h1>Mon annuaire</h1>
        <br>
    
        <?php
            $mysqli = new mysqli('localhost', 'annuaire', '435326co777', 'annuaire') or die(mysqli_error($mysqli));
            $result = $mysqli->query("SELECT * FROM data") or die($mysqli->error);
       
            //Renvoie un tableau associatif avec le nom des champs
            //pre_r($result->fetch_assoc());

            //Function qui permet de recuperer les info de la bdd et qui affiche un tableau
            // function pre_r($array) {
            //     echo '<pre>';
            //     print_r($array);
            //     echo '</pre>';
            // }
        ?>
           

            <div class="row justify-content-center">
                <table class="table">
                    <thead>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Portable</th>
                        <th colspan=2>Action</th>
                    </thead>
                    <?php
                        while ($row = $result->fetch_assoc()) :
                    ?>
                        <tr>
                            <td><?= $row["prenom"]; ?></td>
                            <td><?= $row["nom"]; ?></td>
                            <td><?= $row["email"]; ?></td>
                            <td><?= $row["portable"]; ?></td>
                            <td>
                                <a href="index.php?edit=<?php echo $row['id']; ?>" class="btn btn-info" name="edit">Editer</a>
                                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Effacer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <div class="row justify-content-center">
                <form action="process.php" method="POST">
                    <input type="hidden" name="id" value=<?= $id ?>>
                    <div class="form-group">
                        <label for="">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?php echo $name; ?>" placeholder="Renseignez votre prénom">
                    </div>
                    <div class="form-group">
                        <label for="">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?php echo $lastname; ?>" placeholder="Renseignez votre nom">
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Renseignez votre adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="">Portable</label>
                        <input type="tel" name="portable" class="form-control" value="<?php echo $phone; ?>" placeholder="Renseignez votre numéro de portable">
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

    </div>

   

</body>
</html>