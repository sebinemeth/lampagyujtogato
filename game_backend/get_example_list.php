<?php
session_start();
    require('functions.php');
    $db = new SQLite3("../db/main.db");
    $handle = opendir("examples");
    $examples = array();
    $result = $db->query("select * from results where lesson_number=".$_POST['lesson_number']." and uid like '".$_SESSION['uid']."'");
    $history = array();
    while($row = $result->fetchArray(SQLITE3_ASSOC))
        array_push($history,$row);
    while($entry = readdir($handle)) {
        if(!preg_match("#(\d+)_(\d+)\.json#",$entry,$matches) || $matches[1] != $_POST['lesson_number'])
            continue;
        $prev_score = 0;
        $rating = 0;
        foreach($history as $row) {
            if($row['example_number'] == $matches[2]-1)
                $prev_score = $row['msg'];
            if($row['example_number'] == $matches[2])
                $rating = $row['msg'];
        }
        //$locked = $matches[2] != 1 && $prev_score == 0;
        $locked = !valid_example($db,$matches[1],$matches[2],$_SESSION['uid']);
        array_push($examples,array("lesson_number"=>$matches[1],"example_number"=>$matches[2],"locked"=>$locked,"rating"=>$rating));
    }
    echo json_encode($examples, 256);//JSON_UNESCAPED_UNICODE);
    closedir($handle);
    $db->close();
?>