<?php
session_start();
require_once 'connection.php';
// Check if the session variable user_id exists
if (isset($_SESSION['user_id'])) {
    // User is logged in
    $userLoggedIn = true;
} else {
    // User is not logged in
    $userLoggedIn = false;
}


if (isset($_GET['numVersion'])) {
    try {
        $numVersion = $_GET['numVersion'];
        $sql = "SELECT * 
                FROM evenement 
                INNER JOIN version ON evenement.idEvenement = version.idEvenement
                WHERE numVersion = :numVersion";
        $result = $DATABASE->prepare($sql);
        $result->bindParam(':numVersion', $numVersion, PDO::PARAM_INT); // Assuming numVersion is an integer
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);

        // Get CapacityRoom and CountTickets if needed
        $CapacityRoom = CapacityRoom($numVersion, $DATABASE);
        $CountTickets = CountTickts($numVersion, $DATABASE);
    } catch (PDOException $error) {
        echo 'Error: ' . $error->getMessage();
    }
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
    <style>
        .fullscreen-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .event-container {
            display: flex;
            margin: 20px;
        }

        .event-image-container {
            flex: 1;
            margin-right: 20px;
        }

        .event-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .event-content {
            flex: 2;
        }

        .ticket-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-table th,
        .ticket-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .ticket-table th {
            background-color: #f2f2f2;
        }

        .ticket-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .ticket-table tr:hover {
            background-color: #ddd;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            margin-right: 10px;
        }
    </style>
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
        <!-- Event details -->
        <div class="event-content">

            <!-- Display event details -->
            <h1><?php echo $row['titre'] ?></h1>
            <img class="event-image" src="../image/<?php echo $row['image']; ?>" alt="Event Image">
            <h3>Description</h3>
            <p><?php echo $row['description'] ?></p>
            <!-- Ticket tariffs and purchase button -->
            <div class="tariffs">
                <!-- Input fields for ticket quantities -->
                <label for="tariff1">Tariff Reduit: $50</label>
                <input type="number" id="tariff1" class="tariff-input" min="0" value="0">
                <label for="tariff2">Tariff Normal: $100</label>
                <input type="number" id="tariff2" class="tariff-input" min="0" value="0">
            </div>
            <?php
            // Check if the user is logged in
            if ($userLoggedIn) {
                if ($CountTickets < $CapacityRoom) {
                    // Display Buy Tickets button with onclick event to show popup
                    echo "<button onclick='showPopup()'>Buy Tickets</button>";
                } else {
                    // Display Sold out button if tickets are sold out
                    echo "<button class='button bg-secondary text-white' type='button' disabled>Sold out</button>";
                }
            } else {
                // Display Login button with onclick event to redirect to login page
                echo "<button onclick='redirectToLogin()'>Login to Buy Tickets</button>";
            }
            ?>
        </div>
    </div>

    <div class="fullscreen-overlay" id="overlay">
        <!-- Popup content -->
        <div class="popup" id="popupContent">
            <h2>Confirm Purchase</h2>
            <h4><?php echo $row['titre'] ?></h4>
            <table class="ticket-table">
                <thead>
                    <tr>
                        <th>Type of Ticket</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tariff Reduit</td>
                        <td id="quantity1">0</td>
                        <td id="totalPrice1">$0</td>
                    </tr>
                    <tr>
                        <td>Tariff Normal</td>
                        <td id="quantity2">0</td>
                        <td id="totalPrice2">$0</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td id="totalPrice">$0</td>
                    </tr>
                </tbody>
            </table>
            <div class="button-container">
                <button onclick="confirmPurchase()">Confirm</button>
                <button onclick="closePopup()">Cancel</button>
            </div>
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
    // Function to show the popup
    function showPopup() {
        const overlay = document.getElementById('overlay');
        const popupContent = document.getElementById('popupContent');
        const tariff1Quantity = document.getElementById('tariff1').value;
        const tariff2Quantity = document.getElementById('tariff2').value;
        const totalPrice1 = tariff1Quantity * 50;
        const totalPrice2 = tariff2Quantity * 100;
        const totalPrice = totalPrice1 + totalPrice2;

        // Update table with ticket quantities and total prices
        document.getElementById('quantity1').textContent = tariff1Quantity;
        document.getElementById('quantity2').textContent = tariff2Quantity;
        document.getElementById('totalPrice1').textContent = '$' + totalPrice1;
        document.getElementById('totalPrice2').textContent = '$' + totalPrice2;
        document.getElementById('totalPrice').textContent = '$' + totalPrice;

        // Show the overlay and popup
        overlay.style.display = 'flex';
        popupContent.style.display = 'block';
    }

    // Function to close the popup
    function closePopup() {
        const overlay = document.getElementById('overlay');
        const popupContent = document.getElementById('popupContent');

        // Hide the overlay and popup
        overlay.style.display = 'none';
        popupContent.style.display = 'none';
    }

    // Function to confirm purchase
    function confirmPurchase() {

        window.location.href = "purchase_process.php";
    }

    // Function to redirect to login page
    function redirectToLogin() {
        window.location.href = "Loginpage.php";
    }

    // Menu toggle functionality
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