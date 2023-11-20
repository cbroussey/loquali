<?php
    session_start();    
    $_SESSION['pays'] = $_POST['pays'];
    $_SESSION['region'] = $_POST['region'];
    $_SESSION['tel'] = $_POST['telephone'];
    header("Location: index.php");
?>