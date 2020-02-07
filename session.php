<?php
session_start();
if(isset($_GET['logout']))
    session_destroy();
if(isset($_GET['submit'])) {
    $_SESSION['uid'] = $_GET['uid'];
    $_SESSION['uemail'] = $_GET['uemail'];
    $_SESSION['uname'] = isset($_GET['uname']) ? $_GET['uname'] : null;
    echo "<p>Beállítva <a href='./'>Tovább a játékra</a></p>";
    var_dump($_SESSION);
}
?>
<form action="" method="get">
    UID: <input type="text" value="<?=(isset($_SESSION['uid']) ? $_SESSION['uid'] : "");?>" name="uid" required><br>
    Uemail: <input type="email" value="<?=(isset($_SESSION['uemail']) ? $_SESSION['uemail'] : "");?>" name="uemail" required><br>
    UName: <input type="text" value="<?=(isset($_SESSION['uname']) ? $_SESSION['uname'] : "");?>" name="uname"><br>
    <input type="submit" name="submit">
</form>
<form action="" method="get">
    <input type="submit" name="logout" value="Kijelentkezés">
</form>
<?php
phpinfo();
?>