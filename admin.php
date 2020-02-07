<?php
$lesson = -1;
$example = -1;
$error = false;
if(isset($_POST['lesson'])) {
    $lesson = intVal($_POST['lesson']);
    if(isset($_POST['example'])) {
        $example = intVal($_POST['example']);
        if(isset($_POST['json'])) {
            $obj = json_decode($_POST['json']);
            //var_dump($obj);
            $error = json_last_error() != JSON_ERROR_NONE;
            if(!$error) {
                $filename = $example == 0 ? 'game_backend/lessons/'.$lesson.'.json' : 'game_backend/examples/'.$lesson.'_'.$example.'.json';
                $fout = fopen($filename, "w") or die("Unable to open file!");
                fwrite($fout, json_encode($obj,128 | 256)); //JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE
                fclose($fout);
                echo "<script>alert('".$filename." elmentve');</script>";
            }
            else
                echo "<script>alert('SIKERTELEN MENTÉS! A szerkesztett fájl nem megfelelő JSON formátumú, a mentetlen változtatások megjelennek a szerkesztőben.');</script>";
        }
    }
}
//var_dump($_POST);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lámpagyújtogató - ADMIN</title>
        <style>
            body {
                font-family: monospace;
            }
            .container {
                width: 100%;
                display: table;
            }
            fieldset {
                display: table-cell;
                float: left;
                min-height: 500px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <fieldset style="width:15%">
                <legend>Tananyagok</legend>
                <?php
                $db = new SQLite3("db/main.db");
                $result = $db->query("select * from lesson order by lesson_number asc");
                $theory = null;
                while($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<form action='' method='POST'><input type='hidden' name='lesson' value='".$row['lesson_number']."'>";
                    echo "<input type='submit' value='".$row['lesson_number'].". ".$row['title']."'".($lesson == $row['lesson_number'] ? " disabled" : "").">";
                    echo "</form>";
                    if($lesson == $row['lesson_number'])
                        $theory = json_decode(file_get_contents('game_backend/lessons/'.$row['lesson_number'].'.json'),true);
                }
                $db->close();
                ?>
            </fieldset>
            <?php if($lesson != -1) { ?>
            <fieldset style="width:10%">
                <legend>Példák</legend>
                <?php
                echo "<form action='' method='POST'><input type='hidden' name='lesson' value='".$lesson."'><input type='hidden' name='example' value='0'>";
                echo "<input type='submit' value='elmélet'".($example == 0 ? " disabled" : "").">";
                echo "</form>";
                $handle = opendir("game_backend/examples");
                while($entry = readdir($handle)) {
                    if(!preg_match("#(\d+)_(\d+)\.json#",$entry,$matches) || $matches[1] != $lesson)
                        continue;
                    echo "<form action='' method='POST'><input type='hidden' name='lesson' value='".$lesson."'><input type='hidden' name='example' value='".$matches[2]."'>";
                    echo "<input type='submit' value='".$matches[2].". példa'".($example == $matches[2] ? " disabled" : "").">";
                    echo "</form>";
                }
                closedir($handle);
                ?>
            </fieldset>
            <?php } if($lesson != -1 && $example != -1) { 
                $filename = $example == 0 ? 'game_backend/lessons/'.$lesson.'.json' : 'game_backend/examples/'.$lesson.'_'.$example.'.json';
            ?>
            <fieldset style="width:60%">
                <legend>Szerkesztő</legend>
                <?=$filename;?> szerkesztése
                <form method="post">
                    <textarea name="json" style="width:100%;height:500px;"><?php
                        echo $error ? $_POST['json'] : file_get_contents($filename,true);
                    ?></textarea>
                    <input type='hidden' name='lesson' value='<?=$lesson?>'>
                    <input type='hidden' name='example' value='<?=$example?>'>
                    <br>
                    <input type="submit" value="Mentés">
                </form>
            </fieldset>
            <?php } ?>
        </div>
    </body>
</html>