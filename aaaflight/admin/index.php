<?php ob_start();?>
<?php require_once('../connections/pdoconnect.php'); 
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

?>

<!DOCTYPE html>
<html lang="en">
   <head>
          <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <title><?php echo $app_title; ?></title>
      <meta name="robots" content="noindex, nofollow">
      <meta content="" name="description">
      <meta content="" name="keywords">
   </head>

<?php require_once('../template/phplink.php'); ?>
<body>

<?php require_once('../template/header.php'); ?>
    <?php require_once('dashboard.php'); ?>



<?php require_once('../template/footer.php');?>   

</body>
</html>
<?php ob_flush(); ?>