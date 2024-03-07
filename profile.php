<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

try {
    $userID = $_SESSION['id'];
        $userDetailsStmt = $conn->prepare("SELECT * FROM utilisateur WHERE idUtilisateur = :userID");
    $userDetailsStmt->bindParam(':userID', $userID);
    $userDetailsStmt->execute();
    $userDetails = $userDetailsStmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p class='alert alert-danger mt-4'>Database Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<body>

    <div class="container">
        <h1 class="mt-4 mb-4">User Profile</h1>

        <?php
        if ($userDetails) {
            // Display user details
            echo "<p><strong>Nom:</strong> " . $userDetails['nom'] . "</p>";
            echo "<p><strong>Prenom:</strong> " . $userDetails['prenom'] . "</p>";
            echo" <p><strong>Mot de passe:</strong> " . $userDetails['motPasse'] . "</p>";
            echo " <button type='button'  class='btn btn-primary'><a href='edit_profile.php'> Modifier <a/></button>";
           
        } else {
            echo "<p class='alert alert-danger mt-4'>User not found.</p>";
        }
        ?>

    </div>


</body>

</html>