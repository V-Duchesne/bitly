<?php
require "../controller/doctype.php";
$bdd = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");

if (isset($_POST["login"])) {
    $options = array(
        'id' => FILTER_SANITIZE_STRING,
        'nom' => FILTER_SANITIZE_STRING,
        'prenom' => FILTER_SANITIZE_STRING,
        'login' => FILTER_SANITIZE_STRING,
        'password' => FILTER_SANITIZE_STRING
    );
    $log = $bdd->prepare('SELECT sel, password, id, nom, prenom
                            FROM user
                            WHERE login = ?');
    $log->execute([$_POST["login"]]);
    $filter = filter_input_array(INPUT_POST, $options);
    $mdp3Md5 = md5($filter['password']);

    while ($result = $log->fetch()) {
        $testPass = $result["sel"] . $mdp3Md5;
        if ($result["password"] == $testPass) {
            $_SESSION["connected"] = "true";
            $_SESSION["userID"] = $result["id"];
            $_SESSION["name"] = $result["nom"] . " " . $result["prenom"];
            header("location: ../../index.php");
            exit();
        }
    }
}
