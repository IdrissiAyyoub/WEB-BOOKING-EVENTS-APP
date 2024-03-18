<?php
session_start();
require_once 'connection.php';

try {
    if (isset($_GET['details'])) {
        $numVersion = $_GET['numVersion'];
        $sql = "SELECT titre, description, dateEvenement, categorie, image, numVersion FROM evenement
                INNER JOIN version ON evenement.idEvenement = version.idEvenement
                WHERE numVersion = :numVersion";
        $stmt = $DATABASE->prepare($sql);
        $stmt->bindParam(':numVersion', $numVersion);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $CapacityRoom = CapacityRoom($numVersion, $DATABASE);
        $CountTickets = CountTickts($numVersion, $DATABASE);
    }

    $userLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
} catch (PDOException $error) {
    echo 'error is: ' . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Detail Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <nav>
        <div class="logo">
            <i class="bx bx-menu menu-icon"></i>
            <span class="logo-name">Farha</span>
        </div>
        <div class="sidebar">
            <div class="logo">
                <i class="bx bx-menu menu-icon"></i>
                <span class="logo-name">Farha</span>
            </div>
            <div class="sidebar-content">
                <ul class="lists">
                    <li class="list">
                        <a href="landingPage.php" class="nav-link">
                            <i class="bx bx-home-alt icon"></i>
                            <span class="link">Home</span>
                        </a>
                    </li>
                    <?php if ($userLoggedIn) : ?>
                        <li class="list">
                            <a href="personalSpace.php" class="nav-link">
                                <i class="bx bx-user icon"></i>
                                <span class="link">User</span>
                            </a>
                        </li>
                        <li class="list">
                            <a href="command.php" class="nav-link">
                                <i class="bx bx-command icon"></i>
                                <span class="link">My command</span>
                            </a>
                        </li>
                        <li class="list">
                            <a href="logout.php" class="nav-link">
                                <i class="bx bx-log-out icon"></i>
                                <span class="link">Logout</span>
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="list">
                            <a href="logout.php" class="nav-link">
                                <i class="bx bx-log-in icon"></i>
                                <span class="link">Login</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="list">
                        <a href="#" class="nav-link">
                            <i class="bx bx-calendar-event icon"></i>
                            <span class="link">Evenement</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="event-container">
        <div class="event-image-container">
            <div class="overlay"></div>
            <img src="image/<?php echo $result['image']; ?>" alt="Event Image">
        </div>
        <div class="event-content">
            <h1><?php echo $result['titre'] ?></h1>
            <p><?php echo $result['description'] ?></p>
            <div class="tariffs">
                <label for="tariff1">Tariff Reduit: $50</label>
                <input type="number" id="tariff1" class="tariff-input" min="0" value="0">

                <label for="tariff2">Tariff Normal: $100</label>
                <input type="number" id="tariff2" class="tariff-input" min="0" value="0">
            </div>



            <?php
            if ($CountTickets < $CapacityRoom) {
                if ($userLoggedIn) {
                    echo "<form action='purchase_process.php' method='POST'>";
                    echo "<input type='hidden' name='event_id' value='" . $result['numVersion'] . "'>";
                    echo "<button type='submit' name='buy'>Buy Tickets</button>";
                    echo "</form>";
                } else {
                    echo "<button onclick='redirectToLogin()'>Login to Buy Tickets</button>";
                }
            } else {
                echo "<button class='button bg-secondary text-white' type='button' disabled>Sold out</button>";
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="section__container footer__container">
            <div class="footer__col">
                <h3>Farha</h3>
                <p>FARHA stands as a premier event booking platform, offering a seamless and convenient method to discover and reserve diverse events, music theatre, and cinema experiences.</p>
                <p>With its user-friendly interface and an extensive array of options, FARHA strives to provide a stress-free experience for individuals seeking the perfect events, music theatre, and cinema bookings.</p>
            </div>
            <div class="footer__col">
                <h4>Company</h4>
                <p>About Us</p>
                <p>Our Team</p>
                <p>Blog</p>
                <p>Book</p>
                <p>Contact Us</p>
            </div>
            <div class="footer__col">
                <h4>Legal</h4>
                <p>FAQs</p>
                <p>Terms & Conditions</p>
                <p>Privacy Policy</p>
            </div>
            <div class="footer__col">
                <h4>Resources</h4>
                <p>Social Media</p>
                <p>Help Center</p>
                <p>Partnerships</p>
            </div>
        </div>
        <div class="footer__bar">
            Copyright © 2024 Web Design Ayyoub. All rights reserved.
        </div>
    </footer>

    <script>
        function redirectToLogin() {
            window.location.href = "Loginpage.php";
        }
    </script>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function submit() {
        document.getElementById("editProfail").submit();
    }




    const navBar = document.querySelector("nav"),
        menuBtns = document.querySelectorAll(".menu-icon"),
        overlay = document.querySelector(".overlay");

    menuBtns.forEach((menuBtn) => {
        menuBtn.addEventListener("click", () => {
            navBar.classList.toggle("open");
        });
    });

    overlay.addEventListener("click", () => {
        navBar.classList.remove("open");
    });
</script>

</html>