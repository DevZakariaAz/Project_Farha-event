<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php include 'formHTML/navbar.html'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"><br>
                <h2 class="text-center mb-4">Connexion</h2>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <form method="post" action="">
                   

                    <div class="form-group">
                        <label for="prenom">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="motPasse">Mot de passe:</label>
                        <input type="password" class="form-control" id="motPasse" name="motPasse" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>

                <p class="mt-3">Pas encore de compte? <a href="register.php">S'inscrire ici</a></p>
            </div>
        </div>
    </div>
    <?php
    require_once('db.php');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $motPasse = $_POST['motPasse'];
        $email = $_POST['email'];

        $query = "SELECT * FROM utilisateur WHERE email = :email AND motPasse = :motPasse ";
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':motPasse', $motPasse);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // echo "nadi";
            $_SESSION['id'] = $user['idUtilisateur'];
            echo $user['idUtilisateur'] . "</br>";
            echo $_SESSION['id'];
            header("Location: allEvent.php");
            // header("Location: allEvent.php");
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
    ?>

    <?php include 'Footer.html'; ?>
</body>

</html>