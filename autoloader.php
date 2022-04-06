<?php
namespace project;
foreach (glob("module/*.php") as $filename) { 
    require_once $filename; 
}
require_once("mail/mail.php");
?>