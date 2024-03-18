<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="loginStyle.css">
    <title>Farha</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fab fa-github"></i></a>
                    <a href="#" class="icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" placeholder="Nom" name="nom">
                <input type="text" placeholder="Prénom" name="prenom">
                <input type="email" placeholder="Email" name="email">
                <input type="password" placeholder="Password" name="motPasse">
                <span><?php if (isset($_SESSION['registre_error'])) {
                            echo $_SESSION['registre_error'];
                            unset($_SESSION['registre_error']);
                        } ?>
                </span>
                <button type="submit" name="submit">Sign Up</button>
            </form>

        </div>
        <div class="form-container sign-in">
            <form method="POST" action="checkLogin.php">
                <h1>Sign In</h1>
                <div class="social-icons">
                </div>
                <span>or use your email password</span>
                <input type="email" placeholder="Email" name="email">
                <input type="password" placeholder="Password" name="motPasse">
                <span><?php if (isset($_SESSION['login_error'])) {
                            echo $_SESSION['login_error'];
                            unset($_SESSION['login_error']);
                        } ?></span>
                <a href="#">Forget Your Password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login" onclick="toggleForm()">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register" onclick="toggleForm()">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('container');
            const currentState = sessionStorage.getItem('formState');

            if (currentState === 'sign-up') {
                container.classList.add('active');
            }
        });

        function toggleForm() {
            const container = document.getElementById('container');
            container.classList.toggle('active');

            // Store the current state in sessionStorage
            const currentState = container.classList.contains('active') ? 'sign-up' : 'sign-in';
            sessionStorage.setItem('formState', currentState);
        }
    </script>
</body>

</html>
<?php
session_start();

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
            $_SESSION['registre_error'] = "<span class='error'>Email already exists</span><br>";
        }
    } else {
        $_SESSION['registre_error'] = "All required.";
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
?>