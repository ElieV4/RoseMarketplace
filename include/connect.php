<?php
	$con=mysqli_connect('localhost', 'root', '', 'rosemarketplace');
    if(!$con){
        die(mysqli_error($con));
    }
?>