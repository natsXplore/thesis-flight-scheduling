<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $SQLcrud = "DELETE FROM `user_menu` WHERE id = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

     $SQLcrud = "DELETE FROM `user_restriction` WHERE `menu_id`=?";
  
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $GoTo = "user_menu_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT * FROM `user_menu` WHERE id = ?";
$db->query($query_rs);
$db->bind(1,htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

$query_rscombo = "SELECT * FROM user_menu WHERE `id`=?";
$db->query($query_rscombo);
$db->bind(1,htmlentities($rs_data['parent_id']));
$rsmenu = $db->rowsingle();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title><?php echo $app_title; ?> </title>
</head>
<?php require_once('../template/phplink.php'); ?>
<body>
     <?php require_once('../template/header.php'); ?>
     <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form id="form1" name="form1" method="post">
        <div class="form-horizontal">
        
        <fieldset>
           

            <div class="form-group">
              <label for="">Name</label>
              <input name="name" type="text" placeholder="" class="form-control input-md" readonly value="<?php echo htmlentities($rs_data['name']); ?>">
            </div>
            <br>
            <div class="form-group">
              <label for="">Link</label>
              <input name="href" type="text" placeholder="" class="form-control input-md" readonly value="<?php echo htmlentities($rs_data['href']); ?>">
            </div>

            <br>
             <div class="form-group">
             <label >Parent Menu</label>
                <input name="href" type="text" placeholder="" class="form-control input-md" readonly value="<?php echo htmlentities($rsmenu['name']);?>">
            </div>
            <br>
            <div class="form-group">       
              <label for="icon">Icon</label>   
              <div class="input-group"> <span class="input-group-text" id="inputGroupPrepend"><i class="<?php echo htmlentities($rs_data['icon']); ?>"> </i></span>
              <input name="icon" id="icon" type="text" placeholder="Icon" class="form-control"  readonly  value="<?php echo htmlentities($rs_data['icon']); ?>">
            </div>

            <br>
            <div class="form-group">
            <label for="">Type</label>
                <input name="type" type="text" placeholder="" class="form-control input-md" readonly value="<?php if (!strcmp(htmlentities($rs_data['type']),'m')) {echo 'Main Menu';}?>">
            </div>

            <br>
            <div class="form-group">
            <label for="">Order</label>
                <input name="type" type="text" placeholder="" class="form-control input-md" readonly value="<?php echo htmlentities($rs_data['order']); ?>">
            </div>

        <br>
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          Are you sure you want to Remove this Record? &nbsp;<button type="submit" class="btn btn-outline-danger" form="form1"><span class="bi-trash"></span> Yes</button>
          <a href="user_menu_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> No</a>
          </div>
        </div>
            
        </fieldset>
    </div>
    <input type="hidden" name="POSTcheck" value="form1">
    <input type="hidden" name="id" id="id" value="<?php echo htmlentities($rs_data['id']);?>">    
</form> 
    
 <!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>	

</body>
</html>
<?php ob_flush(); 
$db->close();
?>
