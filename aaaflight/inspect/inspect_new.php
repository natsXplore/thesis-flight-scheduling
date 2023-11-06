<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=?";
$db->query($query_rs);
$db->bind(1,'Student');
$db->bind(2,'active');
$rsstudent=$db->rowset();
$rss_total=$db->rowcount();

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=?";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$rsinstructor=$db->rowset();
$rsi_total=$db->rowcount();

$query_rs = "select * FROM `lup_aircraft` WHERE aircraft_status=?";
$db->query($query_rs);
$db->bind(1,'active');
$rsaircraft=$db->rowset();
$rsa_total=$db->rowcount();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  
  /*$query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
  $db->query($query_rs);
  $db->bind(1,htmlentities($_POST['slug']));
  $db->bind(2,'Student');
  $rscount_student=$db->rowset();
  $rscount_student_total=$db->rowcount();
  if ($rscount_student_total>0){*/

    $SQLcrud = "INSERT INTO flight_sched (`flight_date`, `time_departure`, `etime_arrival`, `departure_aerodome`, `destination_aerodome`, `route_begin`, `route_end`, `level`, `note`, flight_sched_slug) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['flight_date']));
    $db->bind(2,htmlentities($_POST['time_departure']));
    $db->bind(3,htmlentities($_POST['etime_arrival']));
    $db->bind(4,htmlentities($_POST['departure_aerodome']));
    $db->bind(5,htmlentities($_POST['destination_aerodome']));
    $db->bind(6,htmlentities($_POST['route_begin']));
    $db->bind(7,htmlentities($_POST['route_end']));
    $db->bind(8,htmlentities($_POST['level']));
    $db->bind(9,htmlentities($_POST['note']));
    $db->bind(10,htmlentities($_POST['slug']));
    $db->execute();

    $id=$db->lastinsertid();

    $SQLcrud = "UPDATE flight_sched_passenger SET `flight_sched_id`=? WHERE  flight_sched_slug=?";
    $db->query($SQLcrud);
    $db->bind(1,$id);
    $db->bind(2,htmlentities($_POST['slug']));
    $db->execute();

    $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=? ";
    $db->query($SQLcrud);
    $db->bind(1,'0');
    $db->bind(2,htmlentities($_POST['slug']));
    $db->execute();

    $SQLcrud = "UPDATE lup_aircraft SET `flight_status`=?, flight_sched_slug=? WHERE lup_aircraft_id=?";
    $db->query($SQLcrud);
    $db->bind(1,'Flight Schedule Active');
    $db->bind(2,htmlentities($_POST['slug']));
    $db->bind(3,htmlentities($_POST['aid']));
    $db->execute();

    $doc_ref_no=0;
    $query_rs = "select * FROM `inspection_sched` WHERE doc_ref_year=? ORDER BY inspection_sched_id DESC";
    $db->query($query_rs);
    $db->bind(1,date('Y'));
    $rsis=$db->rowsingle();
    $rsis_total=$db->rowcount();
    if ($rsis_total>0){
      $doc_ref_no=$rsis['doc_ref_no']+1;
    }else{
      $doc_ref_no=1;
    }

    $SQLcrud = "INSERT INTO inspection_sched (`flight_sched_id`, `flight_sched_slug`, `lup_aircraft_id`, `inspection_type`, `doc_ref_no`, `doc_ref_year`,  `effective_date`) VALUES (?,?,?,?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,$id);
    $db->bind(2,htmlentities($_POST['slug']));
    $db->bind(3,htmlentities($_POST['aid']));
    $db->bind(4,'PRE-FLIGHT');
    $db->bind(5,$doc_ref_no);
    $db->bind(6,date('Y'));
    $db->bind(7,$_POST['flight_date']);
    $db->execute();
   
    $GoTo = "flight_plan_list.php";
    header(sprintf("Location: %s", $GoTo));
  
  //}
}

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=?  and date(cpl)>=? and flight_status=?";
$db->query($query_rs);
$db->bind(1,'Student');
$db->bind(2,'active');
$db->bind(3,date('Y-m-d'));
$db->bind(4,'');
$rsstudent=$db->rowset();

$query_rs = "select * FROM `user` WHERE `group`=? and `status`=? and date(cpl)>=? and flight_status=?";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$db->bind(3,date('Y-m-d'));
$db->bind(4,'');
$rsinstructor=$db->rowset();

$query_rs = "select * FROM `lup_aircraft` WHERE `aircraft_status`=? AND date_validity>=? AND flight_status=?";
$db->query($query_rs);
$db->bind(1,'active');
$db->bind(2,date('Y-m-d'));
$db->bind(3,'');
$rsaircraft=$db->rowset();

$query_rs = "select * FROM `lup_route`";
$db->query($query_rs);
$rsroute=$db->rowset();

$query_rs = "select * FROM `lup_level`";
$db->query($query_rs);
$rslevel=$db->rowset();

$query_rs = "select * FROM `lup_basic_settings`";
$db->query($query_rs);
$rsbasic=$db->rowsingle();

$slug=uniqid("fs",true);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>

</head>
    
   
<?php require_once('../template/phplink.php'); ?>

<script type="text/javascript"  language="javascript">

function update_aircraft(aid){  
  
    let var_aid =aid;
    let var_sid = "";
    let var_iid = "";
    let flight_sched_slug = "<?php echo $slug; ?>";
    let action="check_aircraft";
      //Swal.fire('Aircraft  ID: ', aid,"info");
      $.ajax({  
        url:"flight_plan_passenger_save.php",  
        method:"POST",  
        dataType:"json",  
        data:{  
          var_aid:var_aid,
          var_sid:var_sid,
          var_iid:var_iid,
          flight_sched_slug:flight_sched_slug,
          action:action  
        },  
        success:function(data)  
        {  
          let str_error=data.error;
          //Swal.fire('Alert', data.total_seat + " " + str_error.length,'info'); 
          if (parseInt(str_error.length)>0){
            Swal.fire('Error Found!', str_error,'error'); 
            let btn_save = document.getElementById('save');
            btn_save.disabled = true;
          }else{
            let btn_save = document.getElementById('save');
            btn_save.disabled = false;
          }
        } 
      });  
  }

  function update_student(sid){  
  
  let var_aid ="";
  let var_sid = sid;
  let var_iid = "";
  let flight_sched_slug = "<?php echo $slug; ?>";
  let action="check_student";
    //Swal.fire('Student  ID: ', var_sid,"info");
    $.ajax({  
      url:"flight_plan_passenger_save.php",  
      method:"POST",  
      dataType:"json",  
      data:{  
        var_aid:var_aid,
        var_sid:var_sid,
        var_iid:var_iid,
        flight_sched_slug:flight_sched_slug,
        action:action  
      },  
      success:function(data)  
      { 
        let str_error=data.error;
          //Swal.fire('Alert', data.total_seat + " " + str_error.length,'info'); 
          if (parseInt(str_error.length)>0){
            Swal.fire('Error Found!', str_error,'error'); 
            let inputs = document.getElementById(var_sid);
            inputs.checked = false;
            let btn_save = document.getElementById('save');
            btn_save.disabled = false;
          }else{
            let btn_save = document.getElementById('save');
            btn_save.disabled = false;
          }
      } 
    });  
}

function update_instructor(iid){  
  
  let var_aid ="";
  let var_sid = "";
  let var_iid = iid;
  let flight_sched_slug = "<?php echo $slug; ?>";
  let action="check_instructor";
    //Swal.fire('Student  ID: ', var_iid,"info");
    $.ajax({  
      url:"flight_plan_passenger_save.php",  
      method:"POST",  
      dataType:"json",  
      data:{  
        var_aid:var_aid,
        var_sid:var_sid,
        var_iid:var_iid,
        flight_sched_slug:flight_sched_slug,
        action:action  
      },  
      success:function(data)  
      { 
        let str_error=data.error;
          //Swal.fire('Alert', data.total_seat + " " + str_error.length,'info'); 
          if (parseInt(str_error.length)>0){
           Swal.fire('Error Found!', str_error,'error'); 
          }
      } 
    });  
}

 
            //$('#available_room').html(data.return_list);  
            //if (data.error==1){
            //      Swal.fire('Error Found', 'Too many passengers! The Aircraft can only accommodate ' + data.total_seat + 'passengers!','info');
            // }                

            //var t=parseFloat(document.getElementById('per_day').value)*parseFloat(document.getElementById('no_days').value);
            //$('#total_amount').val(t.toFixed(2));                      
            //document.getElementById('name').focus();
            //ShowHideDIV('');

</script>

<body>
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1" onsubmit="return validateForm();">

     <div class="form-horizontal">
        <fieldset>
        <div class="row">

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
            <input required type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo date('Y-m-d');?>">
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Time Departure*</strong></label>
            <input required type="time" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value="09:00:00">
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Estimated Time of Arrival*</strong></label>
            <input required type="time" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="17:00:00">
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
            <input required type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rsbasic['set_originator']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
            <input required type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rsbasic['set_originator']);?>">
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Level</strong></label>
            <select required name="level" id="level" class="form-select" placeholder=" ">
              <?php
                foreach($rslevel as $rs_rowlevel) {  
                ?>
                  <option value="<?php echo $rs_rowlevel['level']?>"><?php echo htmlentities($rs_rowlevel['level']);?></option>
                <?php
                  }
                ?>
            </select>
          </div>

        </div>

        <br>
        <div class="row">
          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Route*</strong></label>
            <select required name="route_begin" id="route_begin" class="form-select" placeholder=" ">
            <?php
              foreach($rsroute as $rs_rowroute) {  
              ?>
                <option value="<?php echo $rs_rowroute['route_code']?>"><?php echo htmlentities($rs_rowroute['route']);?></option>
              <?php
                }
              ?>
            </select>
          </div>

          <div class="form-group col-md-2 col-sm-12">
          <label class="col-sm-12 col-form-label">&nbsp;</label>
            <select required name="route_end" id="route_end" class="form-select" placeholder=" ">
              <?php
                foreach($rsroute as $rs_rowroute) {  
                ?>
                  <option value="<?php echo $rs_rowroute['route_code']?>"><?php echo htmlentities($rs_rowroute['route']);?></option>
                <?php
                  }
                ?>
            </select>
          </div>
        
        <div class="form-group col-md-8 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
            <input type="text" class="form-control"  name="note" id="note" placeholder=" " value="">
          </div>
        </div>
        <br>
          
        <div class="row">
       
          <div class="form-group col-md-4 col-sm-12">
              <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
              <div class="col-sm-12 row">
              <?php foreach ($rsaircraft as $rs_rowaircraft){ ?>
                <div class="form-check form-switch col-sm-12"> <input required onchange="update_aircraft('<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>');" class="form-check-input" type="radio" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
              <?php } ?>
              </div>
          </div>

   
            <div class="form-group col-md-4 col-sm-12">
                <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
                <div class="col-sm-12 row">
                <?php foreach ($rsstudent as $rs_rowstudent){ ?>
                  <div class="form-check form-switch col-sm-12"> <input onchange="update_student('<?php echo 's'.htmlentities($rs_rowstudent['id']);?>');" class="form-check-input" type="checkbox" value="<?php echo htmlentities($rs_rowstudent['id']);?>" id="<?php echo 's'.htmlentities($rs_rowstudent['id']);?>" name="<?php echo 's'.htmlentities($rs_rowstudent['id']);?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
                <?php } ?>
                </div>
            </div>
       

          <div class="form-group col-md-4 col-sm-12">
              <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
              <div class="col-sm-12 row">
              <?php foreach ($rsinstructor as $rs_rowinstructor){ ?>
                <div class="form-check form-switch col-sm-12"> <input required onchange="update_instructor('<?php echo htmlentities($rs_rowinstructor['id']);?>');" class="form-check-input" type="radio" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>"> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
              <?php } ?>
              </div>
          </div>
              
        </div>

        <br>
       
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <button type="submit" class="btn btn-outline-primary" form="form1" id="save"><span class="bi-save"></span> Save</button>
          <a href="flight_plan_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a> 
          </div>
        </div>

    </fieldset>  
  </div>
<input type="hidden" name="POSTcheck" value="form1">
<input type="hidden" name="slug" id="slug" value="<?php echo $slug; ?>">
<input type="hidden" name="status"  id="status" value="for inspection">

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
