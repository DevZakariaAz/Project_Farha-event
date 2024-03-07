<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission to update user information
    $newNom = $_POST['new_nom'];
    $newPrenom = $_POST['new_prenom'];
    $newEmail = $_POST['new_email'];
    $newMotPasse = password_hash($_POST['new_mot_passe'], PASSWORD_DEFAULT);

    try {
        $userID = $_SESSION['id'];

        $updateUserStmt = $conn->prepare("UPDATE utilisateur 
                                          SET nom = :newNom, prenom = :newPrenom, email = :newEmail, motPasse = :newMotPasse 
                                          WHERE idUtilisateur = :userID");
        $updateUserStmt->bindParam(':newNom', $newNom);
        $updateUserStmt->bindParam(':newPrenom', $newPrenom);
        $updateUserStmt->bindParam(':newEmail', $newEmail);
        $updateUserStmt->bindParam(':newMotPasse', $newMotPasse);
        $updateUserStmt->bindParam(':userID', $userID);
        $updateUserStmt->execute();

        // Redirect to the profile page after updating
        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        echo "<p class='alert alert-danger mt-4'>Database Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch user details from the database
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

<head>
    <!-- Include your meta tags, stylesheets, and other head elements -->
    <!-- ... -->
</head>

<body>

    <div class="container">
        <h1 class="mt-4 mb-4">Edit Profile</h1>

        <?php
        if ($userDetails) {
            // Display a form for updating user information
        ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="new_nom">Nouveau Nom:</label>
                    <input type="text" class="form-control" id="new_nom" name="new_nom" value="<?= $userDetails['nom'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_prenom">Nouveau Prenom:</label>
                    <input type="text" class="form-control" id="new_prenom" name="new_prenom" value="<?= $userDetails['prenom'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_email">Nouveau Email:</label>
                    <input type="email" class="form-control" id="new_email" name="new_email" value="<?= $userDetails['email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="new_mot_passe">Nouveau Mot de passe:</label>
                    <input type="password" class="form-control" id="new_mot_passe" name="new_mot_passe" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        <?php
        } else {
            echo "<p class='alert alert-danger mt-4'>User not found.</p>";
        }
        ?>

    </div>

</body>

</html>