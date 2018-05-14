<?php
@session_start();
$jumpage = $_POST['jumpage']; $act = $_POST['act'];
$_SESSION[$act] = $jumpage;
?>