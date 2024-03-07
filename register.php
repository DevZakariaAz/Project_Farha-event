<?php include 'formHTML/navbar.html'; ?>
<br><br>
<h1>Registration Form</h1>

<div class="container">
    <form action="" method="post">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" class="form-control" name="nom" placeholder="Nom" id="nom">
        </div>
        <div class=" form-group">
            <label for="prenom">Prenom :</label>
            <input type="text" class="form-control" name="prenom" placeholder="Prenom" id="prenom">
        </div>
        <div class=" form-group">
            <label for="email">Email :</label>
            <input type="email" class="form-control" name="email" placeholder="Email" id="email">
        </div>
        <div class="form-group">
            <label for="motPasse">Mot dePasse :</label>
            <input type="password" class="form-control" name="motPasse" placeholder="Mot de Passe" id="motPasse">
        </div>
        <div class=" form-btn">
            <input type="submit" class="btn btn-primary" value="Register" name="signUp">
        </div>
    </form>
    <div>
        <p>Already Registered? <a href="login.php">Login Here</a></p>
    </div>
</div>
<?php
include 'db.php';
if (isset($_POST['signUp'])) {
    $name = $_POST['nom'];
    $lastName = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['motPasse'];



    $sql = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Check if any rows were returned
    if ($stmt->rowCount() == 0) {
        echo 'nadiii';
        $sql = "INSERT INTO utilisateur (prenom, nom, email, motPasse) VALUES (:firstName, :lastName, :email, :pw)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':firstName', $name);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pw', $password);
        $stmt->execute();

        session_start();
        $_SESSION['id'] = $conn->lastInsertId();
        header("Location: allEvent.php");
        exit();
    } else {
        echo "Email already exists";
    }
}
?>
<?php include 'Footer.html'; ?>
</body>

</html>
