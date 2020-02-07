<header>
    <div class="homeButtonDiv">
        <?php
        if($page != "home") 
            echo "<button class=\"homeButton\" onclick=\"location.href = './';\">HOME</button>";
        ?>
    </div>
    <div class="titleDiv">
        <h3 class="secondaryTitle">Lámpagyújtogató</h3>
        <h2 class="mainTitle">Főmenü</h2>
    </div>
    <div class="userInfo">
        <!--<div class="userButton">
            <?=($_SESSION['uname'] == null ? "<span title='Miért ez jelenik meg? mert nincs neved, azért!'>Játékos_".$_SESSION['uid']."</span>" : $_SESSION['uname']);?>
        </div>-->
        <a href="./session.php" class="userButton">
            <?=($_SESSION['uname'] == null ? "<span title='Miért ez jelenik meg? mert nincs neved, azért!'>Játékos_".$_SESSION['uid']."</span>" : $_SESSION['uname']);?>
        </a>
    </div>
</header>