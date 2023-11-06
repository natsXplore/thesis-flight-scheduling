<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $SQLcrud = "DELETE FROM lup_aircraft WHERE `lup_aircraft_id` = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();
  
    $SQLcrud = "DELETE FROM lup_aircraft_emergency_radio WHERE `lup_aircraft_id` = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();

    $SQLcrud = "DELETE FROM lup_aircraft_survival_equipment WHERE `lup_aircraft_id` = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();
    
    $GoTo = "aircraft_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$query_rs = "SELECT * FROM lup_aircraft WHERE `lup_aircraft_id` = ?";
$db->query($query_rs);
$db->bind(1, htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

$query_rs = "select * FROM `lup_emergency_radio`";
$db->query($query_rs);
$rsradio=$db->rowset();

$query_rs = "select * FROM `lup_survival_equipment`";
$db->query($query_rs);
$rssurvival=$db->rowset();

$query_rs = "select * FROM `lup_type_of_aircraft`";
$db->query($query_rs);
$rstype=$db->rowset();

$query_rs = "select * FROM `lup_basic_settings`";
$db->query($query_rs);
$rsbasic=$db->rowsingle();

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
        <span id="results_here" class="alert-danger"></span><input type="hidden" id="results_here1">
        <div class="form-group col-md-4 col-sm-12">
                <label >Aircraft ID*</label>
                <input readonly class="form-control" type="text" name="aircraft_id" id="aircraft_id"
              value="<?php if (isset($_POST['aircraft_id'])) echo htmlentities($_POST['aircraft_id']); else echo htmlentities($rs_data['aircraft_id']); ?>" size="32" 
              OnKeyUp="showAjax('aircraft_duplicate.php','txtString',this.value + '&prev_id=<?php echo htmlentities($rs_data['aircraft_id']);?>' , 'results_here');"  placeholder=" ">
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <label for="">Type of Aircraft*</label>
              <select disabled name="type_of_aircraft" id="type_of_aircraft" class="form-select" placeholder=" ">
                <?php
                  foreach($rstype as $row_rstype) {  
                  ?>
                    <option value="<?php echo $row_rstype['type_of_aircraft']?>" <?php if (!(strcmp($row_rstype['type_of_aircraft'], $rs_data['type_of_aircraft']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rstype['type_of_aircraft']);?></option>
                  <?php
                    }
                  ?>
                </select>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <label for="">Seat*</label>
          <input readonly type="text" class="form-control"  name="seat" id="seat" placeholder=" " value="<?php echo htmlentities($rs_data['seat']); ?>">
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <label for="">Color Marking*</label>
          <input readonly type="text" class="form-control"  name="color_marking" id="color_marking" placeholder=" " value="<?php echo htmlentities($rs_data['color_marking']); ?>">
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <label for="">Endurance*</label>
          <input readonly type="text" class="form-control"  name="endurance" id="endurance" placeholder="HH:MM" value="<?php echo htmlentities($rs_data['endurance']); ?>">
        </div>

    </div>  
        <br>

        <div class="row">
          <div class="form-group col-md-3 col-sm-12">
          <label for="">Date of Validity*</label>
            <input readonly type="date" class="form-control"  name="date_validity" id="date_validity" placeholder="" value="<?php echo htmlentities($rs_data['date_validity']); ?>">
          </div>
       
          <div class="form-group col-md-3 col-sm-12">
          <label for="">Crossing Speed*</label>
            <input readonly type="text" class="form-control"  name="crossing_speed" id="crossing_speed" placeholder="" value="<?php echo htmlentities($rs_data['crossing_speed']); ?>">
          </div>
          <div class="form-group col-md-6 col-sm-12">
            <label for="">Other Information</label>
            <input readonly type="text" class="form-control"  name="other_info" id="other_info" placeholder="" value="<?php echo htmlentities($rs_data['other_info']); ?>">
          </div>

          </div>
          
          <br>
          <div class="row">

          <div class="form-group col-md-3 col-sm-12">
            <label class="col-sm-12 col-form-label">Emergency Radio</label>
            <div class="col-sm-12 row">
            <?php foreach ($rsradio as $rs_rowradio){ ?>
              <div class="form-check form-switch col-sm-12"> <input disabled <?php if ($phu->get_survival_equipment(htmlentities($rs_data['lup_aircraft_id']),htmlentities($rs_rowradio['emergency_radio']))==1)  echo "checked";?> class="form-check-input" type="checkbox" id="<?php echo str_replace(" ","-",htmlentities($rs_rowradio['emergency_radio']));?>" name="<?php echo str_replace(" ","-",htmlentities($rs_rowradio['emergency_radio']));?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowradio['emergency_radio']);?></label></div>
            <?php } ?>
            </div>
          </div>

          <div class="form-group col-md-3 col-sm-12">
          <label class="col-sm-12 col-form-label">Survival Equipment</label>
            <div class="col-sm-12 row">
            <?php foreach ($rssurvival as $rs_rowsurvival){ ?>
              <div class="form-check form-switch col-sm-12"> <input disabled <?php if ($phu->get_emergency_radio(htmlentities($rs_data['lup_aircraft_id']),htmlentities($rs_rowsurvival['survival_equipment']))==1)  echo "checked";?> class="form-check-input" type="checkbox" id="<?php echo str_replace(" ","-",htmlentities($rs_rowsurvival['survival_equipment']));?>" name="<?php echo str_replace(" ","-",htmlentities($rs_rowsurvival['survival_equipment']));?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowsurvival['survival_equipment']);?></label></div>
            <?php } ?>
            </div>
          </div>

        </div>
        <br>
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          Are you sure you want to Remove this Record? &nbsp;<button type="submit" class="btn btn-outline-danger" form="form1"><span class="bi-trash"></span> Yes</button>
          <a href="aircraft_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> No</a>
          </div>
        </div>
            
        </fieldset>
    </div>
    <input type="hidden" name="POSTcheck" value="form1">
    <input type="hidden" name="id" id="id" value="<?php echo htmlentities($rs_data['lup_aircraft_id']);?>">    
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
