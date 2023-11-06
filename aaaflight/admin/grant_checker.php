<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$phu=new php_util();

    //$MM_authorizedUsers=$MM_authorizedUsers;
    $MM_restrictGoTo = "../admin/log-in.php";
    require("../admin/grant.php");

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session variables
   
  $_SESSION['MM_Username']  = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl']      = NULL;
  $_SESSION['MM_FullName']  = NULL;
  $_SESSION['MM_ID']        = NULL;
  $_SESSION['MM_Designation'] = NULL;
  $_SESSION['title']        = NULL;

  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['MM_FullName']);
  unset($_SESSION['MM_ID']);
  unset($_SESSION['MM_Designation']); 
  unset($_SESSION['title']);

  $logoutGoTo = "../admin/log-in.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>