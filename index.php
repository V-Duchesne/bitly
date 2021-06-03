<?php
// variable
$titre = "projet bitly";
$description = "Shorten your Link";
require "./src/controller/doctype.php";
require "./src/controller/header.php";
require "./src/controller/hero.php";
// Vérifier si mon url contient la variable superglobales $_GET["q"]
if (isset($_GET["q"])) {
    // on encapsule le shortcut dans une variable
    $shortcut = htmlspecialchars($_GET["q"]);

    // On vérifie si c'est un vrai shortcut
    $bdd = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");
    $requete = $bdd->prepare("SELECT COUNT(*) AS x 
                                FROM links 
                                WHERE shortcut = ?");
    $requete->execute(array($shortcut));

    while ($result = $requete->fetch()) {
        if ($result["x"] != 1) {
            header("location: ../?error=true&message=Adresse url non reconnue, veuillez la racourcir");
            exit();
        }
    }

    // Si l'url est valide, on peut rediriger le visiteur vers l'url cible
    $requete = $bdd->prepare("SELECT * 
                                FROM links
                                WHERE shortcut = ?");
    $requete->execute(array($shortcut));
    while ($result = $requete->fetch()) {
        header("location: " . $result["link"]);
        exit();
    }
}
// Vérifier si formulaire recu
if (isset($_POST["url"]) && !empty($_POST["url"])) {
    // envoyer url dans variable
    // Check si l'url est valide
    // Créer le raccourci de l'url unique
    // Vérifier si l'url à déjà été proposée
    // Envoyer les donées vers la db
    // Afficher l'url raccourcie au visiteur
    // mise en place de la relation url raccourcie + lien physique dans l db

    /*****************************
    // Envoyer url dans variable
     *****************************/
    $url = $_POST["url"];

    /*****************************
    // check si l'url est valide **
     ******************************/
    // avec la fonction filter_var() qui prendra en 1er paramètre
    // la variable à vérifier, et en second FILTER_VALIDATE_URL. Cette fonction va renvoyer un boolean
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        // Si l'url n'est pas un lien, on simule un get qui prends en valeur 
        // un boolean et un message:
        header('location: ../?error=true&message=url non valide');
        // Quand on demande une redirection, il est important de stopper le script juste après
        // l'execution de la redirection (encore + important si plusieures redirection possible sur la page)
        exit();
    }


    /****************************************
    // créer le raccourci de l'url unique  **
     *****************************************/

    // On utilise la fonction crypt, qui prends deux paramètres: la variable à crypter
    // et un grain de sel. Dans ce cas, on utilisera un grain de sel aléatoire qu'on provoquera avec 
    // la fonction rand()
    $shortcut = crypt($url, rand());

    // Vérifier si l'url n'a pas déjà été envoyée dans la db
    $bdd = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");
    // Si le count(*) > 0, cela veut dire que mon adresse existe déjà dans ma bd, je selectionne
    // aussi la colone shortcut pour pouvoir redonner le racourci de l'url
    $requete = $bdd->prepare('SELECT COUNT(*) AS x , shortcut
                            FROM links
                            WHERE link = ? ');
    $requete->execute(array($url));

    // Boucle pour vérifier que l'url n'existe pas déjà dans ma db
    while ($result = $requete->fetch()) {
        if ($result["x"] != 0) {
            // Je récupère l'url raccourcie liée à cette entrée et la place dans une session
            // pour afficher plus bas le raccourcis en question
            $_SESSION["shortcut"] = $result["shortcut"];
            // Je renvoie vers une url qui simule un $_GET et comme je crée un $_Get['erreur]
            // Je vais déclencher l'apparition du message en dessous du formulaire de la page.
            header("location: ../?error=true&message=Adresse déjà raccourcie");
            exit();
        }
    }


    // Si la boucle précédente n'a pas déclenchée d'erreur et donc n'a pas stoppé le script,
    // on peut envoyer vers base de données
    // Si le user n'est pas connecté, on envoie la requête sans user_id
    if (isset($_SESSION["connected"]) && $_SESSION["connected"] == "true") {
        $requete = $bdd->prepare('INSERT INTO links(link, shortcut, id_user) VALUES(?, ?, ?)');
        $requete->execute(array($url, $shortcut, $_SESSION["userID"]));
    } else {
        $requete = $bdd->prepare('INSERT INTO links(link, shortcut, id_user) VALUES(?, ?, ?)');
        $requete->execute(array($url, $shortcut, 0));
    }

    // On redirige avec une simulation de $_GET à qui on attribue une key short 
    // qui contient le raccourci de l'url
    header("location: ../?short=" . $shortcut);
    exit();
}

?>


<section class="link">
    <form action="../" method="post">
        <input type="url" name="url" placeholder="<?= $description ?>">
        <input type="submit" value="Shorten">
    </form>
    <p>By clicking SHORTEN, you are agreeing to Bitly’s <span>Terms of Service</span> and <span>Privacy Policy</span></p>

    <?php
    //si l'url de notre page est un $_GET que qu'un index error existe
    // Afficher à l'utilisateur qu'il s'est gouré
    if (isset($_GET["error"]) && $_GET["message"]) {
        if ($_GET["message"] == "Adresse déjà raccourcie") {
    ?>
            <div class="erreur">
                <h4> <?= $_GET["message"] ?> </h4>
                <h4>L'url raccourcie est:</h4>
                <h3> http://localhost/?q=<?= $_SESSION["shortcut"] ?></h3>
                </h3>
            </div>
        <?php
        } else {
        ?>
            <div class="erreur">
                <h4> <?= $_GET["message"] ?> </h4>
            </div>
        <?php
        }
    }
    // Si il existe la key "Short" dans ma variable superglobale $_GET
    // je peux afficher le lien raccourci de l'url envoyée par le user -->
    else if (isset($_GET["short"])) { ?>
        <div class="erreur">
            <h4>URL Raccourcie:</h4>
            <!-- Le ?q= après mon nom de domaine vient simuler un $_GET -->
            <h3>http://localhost/?q=<?= htmlspecialchars($_GET["short"]) ?></h3>
        </div>
    <?php
    }

    ?>

</section>

<?php
require "./src/controller/sponsor.php";
require "./src/controller/footer.php";
?>