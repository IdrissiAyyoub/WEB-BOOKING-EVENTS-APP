<?php
session_start();
require_once 'connection.php';

if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $Sql = "SELECT titre, dateEvenement, image, numVersion 
            FROM evenement
            INNER JOIN version ON evenement.idEvenement = version.idEvenement
            WHERE (titre LIKE :search OR dateEvenement BETWEEN :startdate AND :enddate)";

    try {
        $result = $DATABASE->prepare($Sql);
        $result->bindParam(':search', $search);
        $result->bindValue(':startdate', $_GET['start-date']);
        $result->bindValue(':enddate', $_GET['end-date']);
        $result->execute();
        $lines = $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo 'error is : ' . $error->getMessage();
    }
} else {
    try {
        $currentTime = date("Y-m-d H:i:s");
        $Sql = "SELECT titre, image, dateEvenement, categorie, numVersion, FROM evenement 
                INNER JOIN version ON evenement.idEvenement = version.idEvenement
                where dateEvenement >= :currentTime
                order by dateEvenement, titre";
        $result = $DATABASE->prepare($Sql);
        $result->bindParam(':currentTime', $currentTime);
        $result->execute();
        $lines = $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $errorr) {
        echo 'error is : ' . $errorr->getMessage();
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />

    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8iUNnJT3H95fjLGOtT5LOq+b8l2dyaa5W7e1BR0M5aF5FkYJd94Y9z" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Document</title>
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
                        <a href="#" class="nav-link">
                            <i class="bx bx-home-alt icon"></i>
                            <span class="link">Home</span>
                        </a>
                    </li>
                    <li class="list">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) : ?>

                            <a href="personalSpace.php" class="nav-link">
                                <i class="bx bx-user icon"></i>
                                <span class="link">User</span>
                            </a>

                        <?php else : ?>
                        <?php endif; ?>
                    </li>
                    <li class="list">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) : ?>
                            <a href="command.php" class="nav-link">
                                <i class="bx bx-command icon"></i>
                                <span class="link">My command</span>
                            </a>
                        <?php else : ?>
                        <?php endif; ?>
                    </li>
                    <li class="list">
                        <a href="#" class="nav-link">
                            <i class="bx bx-calendar-event icon"></i>
                            <span class="link">Evenement</span>
                        </a>
                    </li>
                    <li class="list">
                        <a href="#" class="nav-link">
                            <i class="bx bx-message-rounded icon"></i>
                            <span class="link">Our client</span>
                        </a>
                    </li>
                    <li class="list">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) : ?>
                            <a href="logout.php" class="nav-link">
                                <i class="bx bx-log-out icon"></i>
                                <span class="link">Log Out</span>
                            </a>
                        <?php else : ?>
                            <a href="LoginPage.php" class="nav-link">
                                <i class="bx bx-log-in icon"></i>
                                <span class="link">Login</span>
                            </a>
                        <?php endif; ?>
                    </li>

            </div>
        </div>
        </div>
    </nav>

    <header class="section__container header__container">
        <div class="header__image__container">
            <div class="header__content">
                <h1>Welcome to FARHA events</h1>
                <p>Book events, Music Theatre and Cinema .</p>
            </div>
            <div class="booking__container">
                <form action="" method="GET">
                    <div class="form__group">
                        <div class="input__group">
                            <input type="text" name="search" />
                            <label>Search</label>
                        </div>
                        <p>Search by title</p>
                    </div>
                    <div class="form__group">
                        <div class="input__group">
                            <input type="date" name="start-date" />
                            <label></label>
                        </div>
                        <p>From date</p>
                    </div>
                    <div class="form__group">
                        <div class="input__group">
                            <input type="date" name="end-date" />
                            <label></label>
                        </div>
                        <p>To date</p>
                    </div>
                    <div class="form__group">
                        <div class="input__group">
                            <select name="categoryFilter" id="categoryFilter">
                                <option value="">Select Category</option>
                                <option value="Musique">Musique</option>
                                <option value="Cinéma">Cinéma</option>
                                <option value="Théatre">Théatre</option>
                            </select>

                        </div>

                        <p>Categorie</p>
                    </div>
                    <button type="submit" class="btn"><i class="ri-search-line"></i></button> <!-- Move button inside the form -->
                </form>
            </div>
        </div>
    </header>

    <section class="section__container popular__container">
        <h2 class="section__header">Upcoming events</h2>
        <div class="popular__grid">
            <?php foreach ($lines as $row) : ?>
                <?php
                $numVersion = $row['numVersion'];
                $CapacityRoom = CapacityRoom($numVersion, $DATABASE);
                $CountTickts = CountTickts($numVersion, $DATABASE);
                ?>
                <div class="popular__card">
                    <img src="/image/<?php echo $row['image']; ?>" alt="" />
                    <div class="popular__content">
                        <div class="popular__card__header">
                            <h4><?php echo $row['titre']; ?></h4>
                            <h4><?php echo $row['dateEvenement']; ?></h4>
                        </div>
                        <form method="get" action="details.php">
                            <input type="hidden" name="numVersion" value="<?php echo $row["numVersion"]; ?>">
                            <?php if ($CountTickts < $CapacityRoom) {
                                echo "<button type='submit' name='details'>Buy</button>";
                            } else {
                                echo "<button type='submit' name='details'>Sold Out</button>";
                            } ?>
                        </form>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="client">
        <div class="section__container client__container">
            <h2 class="section__header">What our clients say</h2>
            <div class="client__grid">
                <div class="client__card">
                    <img src="" alt="client" />
                    <p>
                        The event booking process with FARHA was smooth and the confirmation was immediate.
                        I highly recommend FARHA for seamless event, music theatre, and cinema bookings.
                    </p>
                </div>
                <div class="client__card">
                    <img src="" alt="client" />
                    <p>
                        Navigating through the FARHA event booking process was effortless, and I received instant confirmation.
                        I highly recommend FARHA for hassle-free bookings of events, music theatre, and cinema.
                    </p>
                </div>
                <div class="client__card">
                    <img src="" alt="client" />
                    <p>
                        The FARHA event booking experience was incredibly smooth, with instant confirmation adding to the convenience.
                        I enthusiastically recommend FARHA for stress-free reservations of events, music theatre, and cinema.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="section__container footer__container">
            <div class="footer__col">
                <h3>WDM&Co</h3>
                <p>
                    FARHA stands as a premier event booking platform, offering a seamless and convenient method to discover and reserve diverse events, music theatre, and cinema experiences.
                </p>
                <p>
                    With its user-friendly interface and an extensive array of options, FARHA strives to provide a stress-free experience for individuals seeking the perfect events, music theatre, and cinema bookings.
                </p>
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
</body>

</html>