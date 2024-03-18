<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'connection.php';
  

    $email = $_POST['email'];
    $password = $_POST['motPasse'];

    $sql = "SELECT idUtilisateur FROM utilisateur WHERE email = :email AND motPasse = :motPasse";
    $stmt = $DATABASE->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':motPasse', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['idUtilisateur'];
        header("Location: landingPage.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: loginPage.php");
        exit;
    }
}


