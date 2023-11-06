<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); 

$db=new DatabaseConnect();
$menu_id=htmlentities($_GET['menu_id']);
$groupid=htmlentities($_GET['group']);

  	  $query_check = "SELECT * FROM user_restriction WHERE `group`=? and `menu_id`=?";	
	  $db->query($query_check);
	  $db->bind(1,$groupid);
	  $db->bind(2,$menu_id);
	  $rscheck = $db->rowsingle();
	  $totalRows_rscheck = $db->rowcount();
		
	  if ($totalRows_rscheck>0) {
		//REMOVE
		$deleteSQL = "DELETE FROM `user_restriction` WHERE `group`=? and `menu_id`=?";
		$db->query($deleteSQL);
		$db->bind(1,$groupid);
	    $db->bind(2,$menu_id);
		$db->execute();	
	   }else{
	   	//INSERT
	   	$SQLcrud = "INSERT INTO user_restriction (`group`,`menu_id`) VALUES (?,?)";
		$db->query($SQLcrud);
		$db->bind(1,$groupid);
	  	$db->bind(2,$menu_id);
		$db->execute();
	   }

?>
<?php ob_flush(); ?>