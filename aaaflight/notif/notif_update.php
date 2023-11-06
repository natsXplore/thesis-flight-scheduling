<?php ob_start(); ?><?php require_once('../connections/pdoconnect.php'); ?>
<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();



    $SQLcrud = "UPDATE `notif` SET `read`=? WHERE `notif_id`=?";
  
    $db->query($SQLcrud);
    $db->bind(1,htmlentities('yes'));
    $db->bind(2,htmlentities($_GET['recordID']));
    $db->execute();


    $GoTo = "notif_list.php";
    header(sprintf("Location: %s", $GoTo));

ob_flush(); 
$db->close();
?>
