<?php
    session_start();

    $db = new SQLite3("../db/main.db");
    $result = $db->query("select lesson_number, title, req_xp, force_available as fa, (select sum(msg) xp from results where results.lesson_number = lesson.lesson_number and uid like '".$_SESSION['uid']."') xp from lesson order by lesson_number asc");
    $lessons = array();
    $next_locked = false;
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $json_data = json_decode(file_get_contents('lessons/'.$row['lesson_number'].'.json'),true);
        $row['json'] = $json_data;
        $row['fa'] = $row['fa'] == 1;
        $row['locked'] = $next_locked && !$row['fa'];
        $next_locked = $row['xp'] < $row['req_xp'];
        array_push($lessons,$row);
    }
    echo json_encode($lessons, 256);//JSON_UNESCAPED_UNICODE);
    $db->close();
?>