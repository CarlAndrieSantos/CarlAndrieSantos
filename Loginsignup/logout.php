<?php

session_start();

if(isset($_SESSION['user_id']))
{
	unset($_SESSION['user_id']);

}

header("Location: /CODEBEGIN/group10/Homepage/home.html");
die;