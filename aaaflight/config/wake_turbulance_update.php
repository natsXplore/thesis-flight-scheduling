<?php ob_start(); ?><?php require_once('../connections/pdoconnect.php'); ?>
<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

      $SQLcrud = "UPDATE `lup_wake_turbulance` SET `code`=?, `description`=? WHERE `lup_wake_turbulance_id`=?";
  
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['code']));
    $db->bind(2,htmlentities($_POST['description']));
    $db->bind(3,$_POST['id']);
    $db->execute();


    $GoTo = "wake_turbulance_list.php";
    header(sprintf("Location: %s", $GoTo));

}
  
$query_rs = "SELECT * FROM `lup_wake_turbulance` WHERE `lup_wake_turbulance_id` = ?";
$db->query($query_rs);
$db->bind(1,htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

?>
<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>
</head>
    
<script type="text/javascript"  language="javascript">
    function validateForm(){
        var x = document.forms["form1"]["results_here1"].value;
        if (x == null || x == "") { return true; }
        else { document.getElementById("results_here").innerHTML ="Record NOT Save. Duplicate Record Found!";document.getElementById("code").focus();return false; }
    }
</script>
<?php require_once('../template/phplink.php'); ?>
<!--
<script type="text/javascript">
 $(document).ready(function () {
  $('#Date').datepicker({format: "yyyy-mm-dd",autoclose:true}); /*input ID*/

});
</script>
-->
<body>
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="POST" id="form1" name="form1" onsubmit="return validateForm();">
  
    <div class="form-horizontal">
    <fieldset>    
    <div class="row">
        <span id="results_here" class="alert-danger"></span><input type="hidden" id="results_here1">
        <div class="form-group col-md-4 col-sm-12">
                <label >Code*</label>
                <input required class="form-control" type="text" name="code" id="code"
              value="<?php if (isset($_POST['code'])) echo htmlentities($_POST['code']); else echo htmlentities($rs_data['code']); ?>" size="32" 
              OnKeyUp="showAjax('wake_turbulance_duplicate.php','txtString',this.value + '&prev_id=<?php echo htmlentities($rs_data['code']);?>' , 'results_here');"  placeholder=" ">
        </div>
        <br>
        <div class="form-group col-md-8 col-sm-12">
                <label >Description*</label>
                <input required class="form-control" type="text" name="description" id="description"
              value="<?php if (isset($_POST['description'])) echo htmlentities($_POST['description']); else echo htmlentities($rs_data['description']); ?>" size="32">
        </div>
    </div>
        <br>
        <div class="form-group">
          <div class="col-md-2"></div>
          <div class="col-md-10">
              <button type="submit" class="btn btn-outline-primary" form="form1"><span class="bi-save"></span> Save</button>
          <a href="wake_turbulance_list.php" class="btn btn-outline-danger"><span class="bi-x-octagon"></span> Cancel</a>
          </div>
        </div>

    </fieldset>    
    </div> 
    
  <input type="hidden" name="POSTcheck" value="form1">
  <input type="hidden" name="id" value="<?php echo htmlentities($rs_data['lup_wake_turbulance_id']); ?>">
  
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
