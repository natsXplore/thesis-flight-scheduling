<?php require_once('../connections/pdoconnect.php'); 

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$var_seat = $_POST['var_seat'];
$return_seat=0;
$error="";

$query_rs = "select * FROM `aircraft` WHERE lup_aircraft_id=?";
$db->query($query_rs);
$db->bind(2,$var_seat);
$rs=$db->rowsingle();

$return_seat= $rs['seat'];

//--------------------------------------------------

$output = array(  
    'return_seat' => $return_seat,
    'error'      =>  $error

);
             
echo json_encode($output); 
?>