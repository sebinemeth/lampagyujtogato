<?php
    session_start();
    require('functions.php');

    $lesson = intVal($_POST['lesson']);
    $example = intVal($_POST['example']);
    $db = new SQLite3("../db/main.db");
    $obj = valid_example($db,$lesson,$example,$_SESSION['uid']) ?
        get_example($db,$lesson,$example,$_SESSION['uid']) :
        array(
            "error"=>"Nem elérhető pálya"
        );
    echo json_encode($obj, 256);//JSON_UNESCAPED_UNICODE);
    $db->close();
?>