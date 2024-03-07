<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farha Events</title>
    <link rel="stylesheet" href="css/mainstyle.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light glass fixed-top">
        <a class="navbar-brand" href="AllEvent.php">Farha Events</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="AllEvent.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events">Events</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profile
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="register.php">Sign In</a>
                        <a class="dropdown-item" href="login.php">Log In</a>
                        <a class="dropdown-item" href="logout.php">Log Out</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">my space</a>
                    </div>
                </li>
            </ul>
            <form method="post" action="" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" id="searchTitle" name="searchTitle" type="search" placeholder="Search" aria-label="Search">
                <input type="submit" class="btn btn-primary" name="search" value="search">
            </form>

        </div>
    </nav>




    <!-- Image Carousel -->
    <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint temporibus labore ex. Accusamus cumque totam
        doloremque quidem sint praesentium cupiditate, autem atque,
        reprehenderit necessitatibus, aut aspernatur voluptas provident ullam commodi.</p> -->
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="img/DJ VAN.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="img/khnifist Rmad.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="img/Concert.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <?php
    session_start();

    include 'db.php';

    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch categories from the database
        $categoriesStmt = $conn->prepare("SELECT DISTINCT categorie FROM evenement");
        $categoriesStmt->execute();
        $categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);

    ?>
        <div class="container mt-4">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="input-group">
                            <select class="custom-select" id="categoryFilter" name="categoryFilter">
                                <option value="" selected>All Categories</option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                <?php }; ?>
                            </select>
                            <div class="input-group-append">
                                <button id="categoryFilterButton" class="btn btn-outline-secondary" type="submit" name="categoryFilterButton">Apply</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 offset-md-3">
                        <div class="input-group">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
        if (isset($_GET['categoryFilterButton'])) {
            if($_GET['categoryFilter'] != 'All Categories'){
                $categoriesQuery = "SELECT * FROM evenement WHERE categorie = :categoryFilter";
            $stmt = $conn->prepare($categoriesQuery);
            $stmt->bindParam(':categoryFilter', $_GET['categoryFilter']);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Handle the result as needed
        }




        if (isset($_POST['search'])) {
        if (!empty($_POST['searchTitle'])) {
        //htmlspecialchars() : Convert special characters to HTML entities
        $searchTitle = htmlspecialchars($_POST['searchTitle']);
        $sql = $conn->prepare("SELECT * FROM evenement
        INNER JOIN version ON evenement.idEvenement = version.idEvenement
        WHERE dateEvenement > CURRENT_DATE AND titre LIKE :searchTitle");
        $searchParam = '%' . $searchTitle . '%';
        $sql->bindParam(':searchTitle', $searchParam);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        }
        }

        if (isset($_SESSION['id'])) {
        $utilisateur = "SELECT * FROM utilisateur WHERE idUtilisateur = :idUtilisateur";
        $stmt = $conn->prepare($utilisateur);
        $stmt->bindParam(':idUtilisateur', $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($result)) {
        echo '<div class="alert alert-success mt-3" role="alert">';
            echo 'Welcome back, <strong>' . $result['prenom'] . ' ' . $result['nom'] . '</strong>!';
            echo '</div>';
        }
        } else {
        echo '<div class="alert alert-danger mt-3" role="alert">';
            echo 'You are not logged in!';
            echo '</div>';
        }

        ?>
    <?php
        $evenmentQuery = "SELECT * FROM evenement 
                       INNER JOIN version ON evenement.idEvenement = version.idEvenement 
                       WHERE dateEvenement > CURRENT_DATE  ";
        $salleCapacityExceeded = false;
        $buttonText = "View Details";
        $buttonClass = "btn btn-secondary";

        if ($salleCapacityExceeded) { 
            $buttonText = "Sold Out";
            $buttonClass .= " disabled";
        }
        $sql = $conn->prepare($evenmentQuery);

        $sql->execute();


        echo "<div id= 'events' class='container mt-4'>";
        echo "<div class='row'>";

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='col-lg-3 rounded col-md-6 mb-4'>";
            echo "<div class='card h-100 ' >";
            $imagePath = 'img/' . $row['image'];
            echo "<img src='$imagePath' class='card-img-top' alt='Event Image' style='height: 200px;'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['titre'] . "</h5>";
            echo "<p class='card-text'>" . $row['description'] . "</p>";
            echo "<p class='card-text'>Category: " . $row['categorie'] . "</p>";
            echo "<p class='card-text'>Date: " . $row['dateEvenement'] . "</p>";
            echo "<a href='Details.php?numVersion=" . $row['numVersion'] . "' class='$buttonClass' style='width: 40%; font-size: 10px;'>$buttonText</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
    } catch (PDOException $e) {
        echo 'Failed: ' . $e->getMessage();
    }
    ?>
    <?php include 'Footer.html'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
<script>

</script>

</html>