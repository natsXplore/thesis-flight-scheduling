<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();


if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  
    $SQLcrud = "INSERT INTO lup_flight_rules (`flight_rules`) VALUES (?)";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['flight_rules']));
    $db->execute();
  
    $GoTo = "flight_rules_list.php";
    header(sprintf("Location: %s", $GoTo));
}

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
        else { document.getElementById("results_here").innerHTML ="Record NOT Save. Duplicate Record Found!";document.getElementById("flight_rules").focus();return false; }
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
<form method="post" name="form1" id="form1" onsubmit="return validateForm();">

     <div class="form-horizontal">
        <fieldset>

         
        <span id="results_here" class="alert-danger"></span> <input type="hidden" id="results_here1">

        <div class="form-group">
        <label for="flight_rules">Flight Rules</label>
          <input required type="text" class="form-control"  name="flight_rules" id="flight_rules" placeholder=" " value="<?php if (isset($_POST['flight_rules'])) echo $_POST['flight_rules'];?>" 
                  OnKeyUp="showAjax('flight_rules_duplicate.php','txtString',this.value, 'results_here');" Onblur="showAjax('flight_rules_duplicate.php','txtString',this.value, 'results_here');" >
          
        </div>
   
        <br>
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <button type="submit" class="btn btn-outline-primary" form="form1"><span class="bi-save"></span> Save</button>
          <a href="flight_rules_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a> 
          </div>
        </div>

    </fieldset>  
  </div>
<input type="hidden" name="POSTcheck" value="form1">
</form>

<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>

</body>
</html>
<?php
$db->close();
?>
