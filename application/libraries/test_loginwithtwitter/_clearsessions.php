<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();
$_SESSION = array();
session_destroy();
 
/* Redirect to page with the connect to Twitter option. */
header('Location: ./connect.php');
//header('Location: ./connect.php');
