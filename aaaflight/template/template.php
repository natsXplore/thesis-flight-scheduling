<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); 
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();
$query_rs = "select * FROM table order by field1";
$db->query($query_rs);
$rs=$db->rowset();

?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title></title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>
<?php require_once('../template/header.php'); ?>
  <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->

	

<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>
</body>
</html>
<?php ob_flush(); ?>