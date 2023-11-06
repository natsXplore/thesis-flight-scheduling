<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

  $allowed = array('png');
  $filename_logo="../images/logo.png";
  
  if(isset($_FILES['image-upload1']) && $_FILES['image-upload1']['error'] == 0){
    $extension = pathinfo($_FILES['image-upload1']['name'], PATHINFO_EXTENSION);
    if(move_uploaded_file($_FILES['image-upload1']['tmp_name'], $filename_logo)){  
    }
  }

  $filename_front_image="../images/front.png";
  
  if(isset($_FILES['image-upload2']) && $_FILES['image-upload2']['error'] == 0){
    $extension = pathinfo($_FILES['image-upload2']['name'], PATHINFO_EXTENSION);
    if(move_uploaded_file($_FILES['image-upload2']['tmp_name'], $filename_front_image)){  
    }
  }

  $insertGoTo = "../admin/index.php";
	header(sprintf("Location: %s", $insertGoTo));

}

clearstatcache();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title><?php echo $app_title; ?>  </title>

</head>
       
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
        <div class="card-header"><h5 class="card-title"><strong>Upload Logo or Background</strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form id="upload" name="upload" method="post" enctype="multipart/form-data">

  <div class="col-md-4" align="center">
    <div class="card" >
      <img id="image_preview1" src="../images/logo.png" class="card-img-top img-fluid" />
      <div class="card-body">
        <label for="image-upload1" class="form-label">Upload Logo  (Type: .png | Max. 2MB)</label>
          <input class="form-control" type="file" id="image-upload1" name="image-upload1" onchange="preview1()"  accept="image/png">
      </div>
    </div>

  </div>

  <div class="col-md-4" align="center">
    <div class="card" >
      <img id="image_preview2" src="../images/front.png" class="card-img-top img-fluid"/>
      <div class="card-body">
        <label for="image-upload2" class="form-label">Upload Background Image  (Type: .png | Max. 2MB)</label>
        <input class="form-control" type="file" id="image-upload2" name="image-upload2" onchange="preview2()" accept="image/png">
        </div>
    </div>
  </div>

  <button name="submit" id="submit" type="submit" class="btn btn-primary"  role="button" data-toogle="tooltip"  data-placement="bottom" title="Save Image"><span class="bi bi-save"></span> Submit</button>
  <a href="../admin/index.php" class="btn btn-danger hidelink "><span class="bi-x-octagon"></span> Cancel</a>

  <input type="hidden" name="POSTcheck" value="form1">
  </form>

<script>
  function preview1() {
    image_preview1.src = URL.createObjectURL(event.target.files[0]);
  }
  function clearImage1() {
    document.getElementById('image-upload1').value = null;
    image_preview1.src = "";
  }

  function preview2() {
    image_preview2.src = URL.createObjectURL(event.target.files[0]);
  }
  function clearImage2() {
    document.getElementById('image-upload2').value = null;
    image_preview2.src = "";
  }
  </script>

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
