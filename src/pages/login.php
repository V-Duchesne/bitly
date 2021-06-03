<?php
$titre = "projet bitly";
$description = "Shorten your Link";
require "../controller/doctype.php";
require "../controller/header.php";
?>
<form action="connect.php" method="POST" class="login">
    <label for="login">Login</label>
    <input type="text" name="login" required>
    <label for="password">Password</label>
    <input type="password" name="password" required>
    <input type="submit">
</form>
<?php
require "../controller/sponsor.php";
require "../controller/footer.php";
?>