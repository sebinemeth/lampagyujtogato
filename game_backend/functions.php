<?php
    
    function get_example($db,$lesson,$example,$uid) {
        $result = $db->query("select * from lesson where lesson_number = ".$lesson);
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $json_data = json_decode(file_get_contents('examples/'.$lesson.'_'.$example.'.json'),true);
        $row['example_number'] = intVal($example);
        $row['json'] = $json_data;
        $theory_json = json_decode(file_get_contents('lessons/'.$lesson.'.json'),true);
        $row['theory_json'] = $theory_json;
        $row['hash'] = md5($lesson.'_'.$example.'_'.$uid);
        return $row;
    }

    function valid_lesson($db,$lesson,$uid) {
        return true;
    }

    function lesson_fa($db,$lesson) {
        $result = $db->query("select * from lesson where lesson_number = ".$lesson);
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row['force_available'] == 1;
    }

    function valid_example($db,$lesson,$example,$uid) {
        if(!valid_lesson($db,$lesson,$uid))
            return false;
        if(lesson_fa($db,$lesson))
            return true;
        if($example == 1)
            return true;
        $result = $db->query("select * from results where lesson_number=".$lesson." and uid like '".$uid."'");
        while($row = $result->fetchArray(SQLITE3_ASSOC))
            if($row["example_number"] == $example-1 && $row["msg"] > 0)
                return true;
        return false;
    }
?>