<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $SQLcrud = "DELETE FROM lup_wake_turbulance WHERE `lup_wake_turbulance_id` = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();
  
    $GoTo = "wake_turbulance_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT * FROM lup_wake_turbulance WHERE `lup_wake_turbulance_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

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
        <div class="row">
        <div class="form-group col-md-4 col-sm-12">
                <label >Code*</label>
                <input readonly class="form-control" type="text" name="code" id="code"
              value="<?php if (isset($_POST['code'])) echo htmlentities($_POST['code']); else echo htmlentities($rs_data['code']); ?>" size="32">
        </div>
        <br>
        <div class="form-group col-md-8 col-sm-12">
                <label >Description*</label>
                <input readonly class="form-control" type="text" name="description" id="description"
              value="<?php if (isset($_POST['description'])) echo htmlentities($_POST['description']); else echo htmlentities($rs_data['description']); ?>" size="32">
        </div>
        </div>
        <br>
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          Are you sure you want to Remove this Record? &nbsp;<button type="submit" class="btn btn-outline-danger" form="form1"><span class="bi-trash"></span> Yes</button>
          <a href="wake_turbulance_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> No</a>
          </div>
        </div>
            
        </fieldset>
    </div>
    <input type="hidden" name="POSTcheck" value="form1">
    <input type="hidden" name="id" id="id" value="<?php echo htmlentities($rs_data['lup_wake_turbulance_id']);?>">    
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
