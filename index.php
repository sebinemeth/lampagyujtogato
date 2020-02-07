<?php
session_start();
if(!isset($_SESSION['uid']))
    header("Location:./session.php");
?>
<!doctype html>
<html>
    <head>
        <title>Lámpagyújtogató</title>
        <meta charset="utf-8">
        <!--styles-->
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
        <link rel="stylesheet" href="css/main.css?v=<?=time();?>">
        <!--external libs-->
        <script src="js/libs/jquery-1.8.2.js"></script>
        <script src="js/libs/jquery-ui.min.js"></script>
        <script src="js/libs/jquery.ui.touch-punch.min.js"></script>
        <script src="js/libs/math.min.js"></script>
        <script src="js/libs/jquery.redirect.js"></script>
        <?php
        echo file_exists("js/libs/MathJax-master/latest.js") ?
            '<script src="js/libs/MathJax-master/latest.js?config=TeX-MML-AM_CHTML"></script>' :
            '<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config=TeX-MML-AM_CHTML" async></script>';
        ?>
        
        <!--custom scripts-->
        <script src="js/main.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Part.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Cut.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Resistor.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Switch.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/VSource.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Wire.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Hole.js?v=<?=time();?>"></script>
        <script src="js/circuit/parts/Bulb.js?v=<?=time();?>"></script>
        <script src="js/circuit/Branch.js?v=<?=time();?>"></script>
        <script src="js/circuit/Point.js?v=<?=time();?>"></script>
        <script src="js/circuit/Circuit.js?v=<?=time();?>"></script>
    </head>
    <body>
        <?php include('pontvelunkheader.php'); ?>
        <div class="container">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : "home";
            include("header.php");
            if($page == "game" && $_SERVER['REQUEST_METHOD'] != 'POST')
                header("Location:./");
            switch($page) {
                case "game" :
                    include("game.php");
                    break;
                default :
                    include("home.php");
            }
            include("footer.php");
            ?>
        </div>
    </body>
</html>