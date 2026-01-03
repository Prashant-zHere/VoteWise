<?php

session_start();
if(!isset($_SESSION['user']))
{
    header("Location: ../index.php");
    exit();
}


if(isset($_POST['logout']))
{
    if($_SESSION['user']['user_type'] == 'admin')
    {
        session_destroy();
        header("Location: ../index.php");
    }
    else if($_SESSION['user']['user_type'] == 'candidate' || $_SESSION['user']['user_type'] == 'voter')
    {
        session_destroy();
        header("Location: ../../index.php");
    }
    exit();
}

?>