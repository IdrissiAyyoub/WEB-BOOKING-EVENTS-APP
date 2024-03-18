<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginStyle.css">
    <title>Document</title>
</head>

<body>
    <div class="form-container sign-up">
        <form method="POST" action="register.php">
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
            <span><?php if (isset($_SESSION['register_error'])) {
                        echo $_SESSION['register_error'];
                        unset($_SESSION['register_error']);
                    } ?></span>
            <button type="submit" name="submit">Sign Up</button>
        </form>

    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>