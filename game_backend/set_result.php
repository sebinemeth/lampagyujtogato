<?php
    session_start();
    $db = new SQLite3("../db/main.db");
    $result = $db->query("select count(*) as count from results where uid like '".$_SESSION['uid']."' and lesson_number=".$_POST['lesson_number']." and example_number=".$_POST['example_number']);
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if(intVal($row['count']) == 0)
        $db->query("insert into results (uid, lesson_number, example_number, msg) values ('".$_SESSION['uid']."',".$_POST['lesson_number'].",".$_POST['example_number'].",".$_POST['msg'].")");
?>