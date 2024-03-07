<?php
session_start();
include 'db.php';

if (isset($_GET['numVersion'])) {
    $numVersion = $_GET['numVersion'];

    try {
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch event details based on the numerical version
        $eventDetailsStmt = $conn->prepare("SELECT * FROM evenement 
                                           INNER JOIN version ON evenement.idEvenement = version.idEvenement 
                                           WHERE version.numVersion = :numVersion");
        $eventDetailsStmt->bindParam(':numVersion', $numVersion);
        $eventDetailsStmt->execute();
        $eventDetails = $eventDetailsStmt->fetch(PDO::FETCH_ASSOC);


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Valider'])) {
            $numTickets = $_POST['num_tickets'];
            $tarifType = $_POST['tarif_type'];
            echo "<div class='container mt-4'>";
            echo "<div class='alert alert-success' role='alert'>";
            echo "Achat réussi! Vous pouvez voir les billets et la facture.";
            echo "</div>";
            echo "</div>";

            $purchaseQuery = "INSERT INTO facture (numVersion ,datefacture,idUtilisateur ,idFacture) 
                      VALUES (:numVersion, NOW(),:idUtilisateur,:idFacture)";
            $purchaseStmt = $conn->prepare($purchaseQuery);

            // You need to replace the placeholders with actual values
            $purchaseStmt->bindParam(':numVersion', $numVersion);
            $purchaseStmt->bindParam(':idUtilisateur', $_SESSION['id']);
            $purchaseStmt->bindParam(':idFacture', $numTickets);

            // Execute the query
            $purchaseStmt->execute();
        }
    } catch (PDOException $e) {
        echo "<p class='alert alert-danger mt-4'>Database Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='alert alert-danger mt-4'>Numerical Version not provided.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Detailstyle.css">
    <title>Event Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <?php
        // Display the event details
        if (isset($eventDetails)) {
        ?>
            <h1 class="mt-4 mb-4">Evenement : <?= $eventDetails['titre'] ?></h1>
            <div class="DivDesc">
                <div class="DivImage">
                    <img src="img/<?= $eventDetails['image'] ?>" alt="Event Image" class="img-fluid mb-4">
                </div>
                <div class="DivText">
                    <p><strong>Description:</strong> <?= $eventDetails['description'] ?></p>
                    <p><strong>Date:</strong> <?= $eventDetails['dateEvenement'] ?></p>
                    <p><strong>Lieu:</strong> <?= $eventDetails['categorie'] ?></p>
                    <p><strong>Tarif Normal:</strong> <?= $eventDetails['tarifnormal'] ?> MAD</p>
                    <p><strong>Tarif Reduit:</strong> <?= $eventDetails['tarifReduit'] ?> MAD</p>
                    <?php if (isset($_SESSION['id'])) { ?>
                        <!-- Display the ticket purchase form -->
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="num_tickets">Nombre de billets:</label>
                                <input type="number" class="form-control" id="num_tickets" name="num_tickets" required>
                            </div>
                            <div class="form-group">
                                <label for="tarif_type">Type de tarif:</label>
                                <select class="form-control" id="tarif_type" name="tarif_type">
                                    <option value="normal">Normal</option>
                                    <option value="reduit">Réduit</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="Valider">Valider</button>
                        </form>
                    <?php } else { ?>
                        <p class="alert alert-warning mt-4">Vous devez vous inscrire pour acheter des billets.</p>
                        <p><a href='register.php'>Inscrivez-vous ici</a></p>
                    <?php } ?>
                </div>
            </div>
        <?php
        } else {
            echo "<p class='alert alert-danger mt-4'>Event not found.</p>";
        }
        ?>
    </div>
    <br><br>
    <?php include 'Footer.html'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>