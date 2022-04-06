<?php
require_once('constants.php');
require_once('headers.php');
require_once('Routes.php');
require_once('Response.php');
require_once('Process.php');
require_once('db/dbconn.php');

$uriData=processRoutes();

$process=new Process($uriData);
?>