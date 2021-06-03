<?php
$titre = "projet bitly";
$description = "Shorten your Link";
require "../controller/doctype.php";
require "../controller/header.php";

?>
<form action="input.php" method="POST" class="inscription">
    <label for="nom">Nom</label>
    <input type="text" name="nom" required>
    <label for="prenom">Prénom</label>
    <input type="text" name="prenom" required>
    <label for="login">Login</label>
    <input type="text" name="login" required>
    <?php
    if (isset($_GET["error"])) {
        if ($_GET["message"] == "login utilisé") {
    ?>
            <p class="used">Login déjà utilisé</p>
    <?php
        }
    }
    ?>
    <label for="email">Email</label>
    <input type="email" name="email" required>
    <?php
    if (isset($_GET["error"])) {
        if ($_GET["message"] == "email utilisée") {
    ?>
            <p class="used">Email déjà utilisée</p>
    <?php
        }
    }
    ?>
    <label for="password">Password</label>
    <input type="password" name="password" required>
    <input type="submit">
</form>
<?php
require "../controller/sponsor.php";
require "../controller/footer.php";
?>