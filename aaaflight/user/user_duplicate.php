<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>
<?php 

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
	  $query_check = "SELECT * FROM user WHERE `username` LIKE ?";
	
	  $db->query($query_check);
	  $db->bind(1,$id);
	  $rscheck = $db->rowset();
	  $totalRows_rscheck = $db->rowcount();
		
	  if ($totalRows_rscheck>0) {
			echo "Duplicate User Name Found!";
		}
  }
?>
<?php ob_flush(); ?>