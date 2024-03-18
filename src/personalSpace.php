<?php
session_start();
require_once 'connection.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $user = $DATABASE->prepare("SELECT * FROM utilisateur where idUtilisateur = :user_id");
    $user->bindParam(':user_id', $userId);
    $user->execute();
    $users = $user->fetch(PDO::FETCH_ASSOC);


    if (isset($_POST['submit'])) {
        $editedFname = $_POST['prenom'];
        $editedLname = $_POST['nom'];
        $editedmotPass =  $_POST['motPasse'];
        $update = $DATABASE->prepare("UPDATE utilisateur SET prenom = :new_prenom, nom = :new_nom, motPasse = :new_motPasse where idUtilisateur = :user_id");
        $update->bindParam(':new_prenom', $editedFname);
        $update->bindParam(':new_nom', $editedLname);
        $update->bindParam(':new_motPasse', $editedmotPass);
        $update->bindParam(':user_id', $userId);
        $update->execute();

        header('location: personalSpace.php');
        exit();
    }
}


?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="UserSpaceStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>

        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change Information</a>

                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">

                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Prenom :</label>
                                    <span> <strong><?php echo $users['prenom'] ?></strong></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">nom :</label>
                                    <span> <strong><?php echo $users['nom'] ?></strong></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail :</label>
                                    <span> <strong><?php echo $users['email'] ?></strong></span>

                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label">Change nom :</label>
                                        <input name="nom" type="text" class="form-control" value="<?php echo $users['nom'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">change prenom</label>
                                        <input name="prenom" type="text" class="form-control" value="<?php echo $users['prenom'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Change password</label>
                                        <input name="motPasse" type="password" class="form-control" placeholder="New password" value="<?php echo $users['motPasse'] ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                        <a href="landingPage.php" class=" btn btn-default">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>

</html>