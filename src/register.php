<?php
include('connection.php');

if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['motPasse']) && strlen($_POST['motPasse']) >= 6) {
        if (!verify_user($_POST['email'], $DATABASE)) {
            $insertQuery = "INSERT INTO utilisateur (nom, prenom, email, motPasse) VALUES (:nom, :prenom, :email, :password)";
            $stmInsertUser = $DATABASE->prepare($insertQuery);
            $stmInsertUser->bindParam(':nom', $_POST['nom']);
            $stmInsertUser->bindParam(':prenom', $_POST['prenom']);
            $stmInsertUser->bindParam(':email', $_POST['email']);
            $stmInsertUser->bindParam(':password', $_POST['motPasse']);
            $stmInsertUser->execute();
            echo "Inscription réussie";
        } else {
            $_SESSION['register_error'] = "<span class='error'>Email already exists</span><br>";
            header("Location: loginPage.php");
            exit;
        }
    } else {
        $_SESSION['register_error'] = "All required.";
        header("Location: loginPage.php");
        exit;
    }
}

function verify_user($email, $pdo)
{
    $stmCheckUser = $pdo->prepare("SELECT * FROM utilisateur WHERE email=:email");
    $stmCheckUser->bindParam(':email', $email);
    $stmCheckUser->execute();
    $data = $stmCheckUser->fetchAll(PDO::FETCH_ASSOC);

    if (count($data) > 0) {
        return true;
    } else {
        return false;
    }
}
