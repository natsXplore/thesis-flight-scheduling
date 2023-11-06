<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>
<?php 
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

  $id=htmlentities($_GET['txtString']);
  $findSW=1;
  
  if (isset($_GET['prev_id'])){
	  $prev_id=$_GET['prev_id'];
	  	  
	  if (!(strcmp($prev_id,$id))){
			  $findSW=0;
	  }
	  
  }
  
  if ($findSW==1) {
	  $query_check = "SELECT * FROM lup_flight_rules WHERE `flight_rules` = ?";	
	  $db->query($query_check);
	  $db->bind(1,$id);
	  $rscheck = $db->rowset();
	  $totalRows_rscheck = $db->rowcount();
		
	  if ($totalRows_rscheck>0) {
			echo "Duplicate Record Found!";
		}
  }
?>
<?php ob_flush(); ?>