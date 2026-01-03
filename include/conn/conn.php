<?php
$conn = mysqli_connect("localhost","root","","online_voting");
    
if($conn)
    echo "<script>console.log('Connected successfully');</script>";
else
    echo "<script>console.log('Failed to connect to MySQL: " . mysqli_connect_error() . "');</script>";
    
?>