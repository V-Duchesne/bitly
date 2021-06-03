<?php
$bdd = new PDO("mysql:host=localhost;dbname=bitly;charset=utf8", "root", "");

function grainDeSel($x){
    $chars = '0123456789abcdef';
    $string = '';
    for($i = 0; $i < $x; $i++){
        $string .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $string;
}

$sel = grainDeSel(rand(5,15));

if (isset($_POST["nom"])) {
    $options = array(
        'nom' => FILTER_SANITIZE_STRING,
        'prenom' => FILTER_SANITIZE_STRING,
        'login' => FILTER_SANITIZE_STRING,
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => FILTER_SANITIZE_STRING
    );
    $login = $bdd->prepare('SELECT COUNT(*) AS x
                            FROM user
                            WHERE login = ?');
    $login->execute([$_POST["login"]]);
    while ($result = $login->fetch()) {
        if ($result["x"] != 0) {
            header("location: inscription.php?error=true&message=login utilisé");
            exit();
        }
    }
    $mail = $bdd->prepare('SELECT COUNT(*) AS x
                            FROM user
                            WHERE email = ?');
    $mail->execute([$_POST["email"]]);
    while ($result = $mail->fetch()) {
        if ($result["x"] != 0) {
            header("location: inscription.php?error=true&message=email utilisée");
            exit();
        }
    }
    $result = filter_input_array(INPUT_POST, $options);
    $mdp3Md5 = md5($result['password']);
    $mdp3Md5Sel = $sel . $mdp3Md5;
    $requete = $bdd->prepare('INSERT INTO user(login, nom, prenom, email, password, sel) VALUES(?, ?, ?, ?, ?, ?)');
    $requete->execute([$result["login"], $result["nom"], $result["prenom"], $result["email"], $mdp3Md5Sel, $sel]);
}

header("location: ../../index.php");
