<header>
    <a href="../../index.php"><img src="../../src/img/bitly-logo.png" alt="logo-bitly"></a>
    <nav>
        <ul>
            <li><a href="#">Why Bitly?</a></li>
            <li><a href="#">Solution</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Ressource</a></li>
        </ul>
    </nav>
    <?php
    if (isset($_SESSION["connected"])) {
    ?>
        <nav>
            <ul>
                <li><p>Bonjour <?php echo $_SESSION["name"]?></p></li>
                <li><a href="../../src/pages/disconnect.php">disconnect</a></li>
                <li><a href="../../src/pages/liens.php">Vos liens</a></li>
            </ul>
        </nav>
    <?php
    } else {
    ?>
        <nav>
            <ul>
                <li><a href="../../src/pages/login.php">Login</a></li>
                <li><a href="../../src/pages/inscription.php">Sign Up</a></li>
                <li><a href="#">Get a Quote</a></li>
            </ul>
        </nav>
    <?php
    }
    ?>

</header>