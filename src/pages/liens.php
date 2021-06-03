<?php
$titre = "projet bitly";
$description = "Shorten your Link";
require "../controller/doctype.php";
require "../controller/header.php";
$bdd = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");
$count = "false";

$requete = $bdd->prepare('SELECT count(*) AS x
                            FROM links
                            WHERE id_user = ? ');
$requete->execute([$_SESSION["userID"]]);

while ($result = $requete->fetch()) {
    if ($result["x"] <= 1) {
        $count = "false";
    } else {
        $count = "true";
    }
}

$requete = $bdd->prepare('SELECT *
                            FROM links
                            WHERE id_user = ? ');
$requete->execute([$_SESSION["userID"]]);

if ($count == "false") {
?>
    <section class="sponsor2">
        <div>
            <h2>Vous n'avez pas encore raccourci de liens</h2>
        </div>
    </section>
<?php
} else {
?>
    <section class="liensTableau">
        <table>
            <tr>
                <th>URL</th>
                <th>raccourci</th>
            </tr>
            <?php
            while ($result = $requete->fetch()) {
            ?>
                <tr>
                    <td>
                        <?php echo $result["link"]; ?>
                    </td>
                    <td>
                        <?php echo "http://localhost/?q=" . $result["shortcut"]; ?>
                    </td>
                </tr>

            <?php
            }
            ?>
        </table>
    </section>
<?php
}
require "../controller/sponsor.php";
require "../controller/footer.php";
