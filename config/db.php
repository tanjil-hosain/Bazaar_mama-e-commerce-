<?php

$connect = mysqli_connect("localhost", "root", "", "bazaar_mama");

if (!$connect) {
    die("Connection Failed: " . mysqli_connect_error());
}

echo "Connection Successful";

?>