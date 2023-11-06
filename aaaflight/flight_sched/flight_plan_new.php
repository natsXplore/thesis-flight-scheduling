<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

$msg2="";

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  
    $SQLcrud = "INSERT INTO flight_sched (`flight_date`, `time_departure`, `etime_arrival`, `departure_aerodome`, `destination_aerodome`, `route_begin`, `route_end`, `level`, `note`, flight_sched_slug, training_type) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['flight_date']));
    $db->bind(2,htmlentities(date("G:i",strtotime($_POST['time_departure']))));
    $db->bind(3,htmlentities($_POST['etime_arrival']));
    $db->bind(4,htmlentities($_POST['route_begin']));
    $db->bind(5,htmlentities($_POST['route_begin']));
    $db->bind(6,htmlentities($_POST['route_begin']));
    $db->bind(7,htmlentities($_POST['route_end']));
    $db->bind(8,htmlentities($_POST['level']));
    $db->bind(9,htmlentities($_POST['note']));
    $db->bind(10,htmlentities($_POST['slug']));
    $db->bind(11,htmlentities($_POST['training_type']));
    $db->execute();

    $id=$db->lastinsertid();

    if ($_POST['training_type']=="Touch and Go"){
    $SQLcrud = "INSERT INTO flight_touch_details (`flight_sched_id`, `landing`, flight_date) VALUES (?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($id));
    $db->bind(2,'HANGAR');
    $db->bind(3,htmlentities($_POST['flight_date']));
    $db->execute();
    }

      $SQLcrud = "INSERT INTO flight_sched_passenger (user_id,`flight_sched_id`,lup_aircraft_id,flight_sched_slug) VALUES (?,?,?,?)";
      $db->query($SQLcrud);
      $db->bind(1,$_POST['iid']);
      $db->bind(2,$id);
      $db->bind(3,htmlentities($_POST['aid']));
      $db->bind(4,htmlentities($_POST['slug']));
      $db->execute();

      if(!empty($_POST['sid'])) {

        foreach($_POST['sid'] as $value){
          $SQLcrud = "INSERT INTO flight_sched_passenger (user_id,`flight_sched_id`,lup_aircraft_id,flight_sched_slug) VALUES (?,?,?,?)";
          $db->query($SQLcrud);
          $db->bind(1,$value);
          $db->bind(2,$id);
          $db->bind(3,htmlentities($_POST['aid']));
          $db->bind(4,htmlentities($_POST['slug']));
          $db->execute();
        }

    }

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


    $SQLcrud = "INSERT INTO inspection_sched (`flight_sched_id`, `flight_sched_slug`, `lup_aircraft_id`, `inspection_type`, `doc_ref_no`, `doc_ref_year`,  `effective_date`, inspection_status) VALUES (?,?,?,?,?,?,?,?)";
    $db->query($SQLcrud);
    $db->bind(1,$id);
    $db->bind(2,htmlentities($_POST['slug']));
    $db->bind(3,htmlentities($_POST['aid']));
    $db->bind(4,'PRE-FLIGHT');
    $db->bind(5,$doc_ref_no);
    $db->bind(6,date('Y'));
    $db->bind(7,$_POST['flight_date']);
    $db->bind(8,$_POST['inspection_status']);
    $db->execute();
    $id_inspect=$db->lastinsertid();

    $query_rs = "SELECT * FROM flight_sched_passenger WHERE `flight_sched_id` = ?";
    $db->query($query_rs);
    $db->bind(1, $id);
    $rs_data_notif = $db->rowset();
    $rs_data_notif_total = $db->rowcount();

    foreach ($rs_data_notif as $row_rs_data_notif){
      $SQLcrud = "INSERT INTO notif (`flight_sched_id`, `user_id`, `notification`,user_from, inspection_sched_id) VALUES (?,?,?,?,?)";
      $db->query($SQLcrud);
      $db->bind(1,$id);
      $db->bind(2,htmlentities($row_rs_data_notif['user_id']));
      $db->bind(3,'New Scheduled Flight Plan dated '.htmlentities($_POST['flight_date']));
      $db->bind(4,$_SESSION['MM_FullName']);
      $db->bind(5, $id_inspect);
      $db->execute();
    }

    $GoTo = "flight_plan_list.php";
    header(sprintf("Location: %s", $GoTo));
  

}


$query_rs = "SELECT u.* FROM `user` u WHERE u.`group`=? AND u.`status`=?  and DATE(u.cpl)>=? 
AND ((u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?  AND (fs.flight_status='' OR fs.flight_status='on-flight' OR fs.flight_status IS NULL)))); ";
$db->query($query_rs);
$db->bind(1,'Student');
$db->bind(2,'active');
$db->bind(3,date('Y-m-d'));
$db->bind(4,date('Y-m-d'));
$db->bind(5,date('Y-m-d'));
$rsstudent=$db->rowset();
$rsstudent_total=$db->rowcount();

$query_rs = "SELECT u.* FROM `user` u WHERE u.`group`=? AND u.`status`=?  and DATE(u.cpl)>=? 
AND ((u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?  AND (fs.flight_status='' OR fs.flight_status='on-flight' OR fs.flight_status IS NULL)))); ";
$db->query($query_rs);
$db->bind(1,'Student');
$db->bind(2,'active');
$db->bind(3,date('Y-m-d'));
$db->bind(4,date('Y-m-d'));
$db->bind(5,date('Y-m-d'));
$rsstudent_one=$db->rowsingle();
$rss_total_one=$db->rowcount();

if ($rss_total_one>0){
  $sid='s'.$rsstudent_one['id'];
}else{
  $sid="";
}

$query_rs = "SELECT u.* FROM `user` u WHERE u.`group`=? AND u.`status`=?  and DATE(u.fil)>=? 
AND ((u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (u.id NOT IN (SELECT user_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=? AND (fs.flight_status='' OR fs.flight_status='on-flight' OR fs.flight_status IS NULL)))); ";
$db->query($query_rs);
$db->bind(1,'Instructor');
$db->bind(2,'active');
$db->bind(3,date('Y-m-d'));
$db->bind(4,date('Y-m-d'));
$db->bind(5,date('Y-m-d'));
$rsinstructor=$db->rowset();
$rsinstructor_total=$db->rowcount();

$query_rs = "select * FROM `lup_aircraft` WHERE `aircraft_status`=? AND date_validity>=? AND ((lup_aircraft_id NOT IN (SELECT lup_aircraft_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=?)) OR (lup_aircraft_id NOT IN (SELECT lup_aircraft_id FROM flight_sched_passenger fsp INNER JOIN flight_sched fs ON fs.flight_sched_id=fsp.flight_sched_id 
WHERE fs.flight_date=? AND (fs.flight_status='' OR fs.flight_status='on-flight' OR fs.flight_status IS NULL))))";
$db->query($query_rs);
$db->bind(1,'active');
$db->bind(2,date('Y-m-d'));
$db->bind(3,date('Y-m-d'));
$db->bind(4,date('Y-m-d'));
$rsaircraft=$db->rowset();
$rsaircraft_total=$db->rowcount();


$query_rs = "select * FROM `lup_route` ORDER by route ASC";
$db->query($query_rs);
$rsroute=$db->rowset();

$query_rs = "select * FROM `lup_level`";
$db->query($query_rs);
$rslevel=$db->rowset();

$query_rs = "select * FROM `lup_basic_settings`";
$db->query($query_rs);
$rsbasic=$db->rowsingle();

$slug=uniqid("fs",true);

$time_interval=$phu->get_time_interval('07:00','24:00',$rsbasic['set_flight_time_interval'], date('Y-m-d'));

?>

<!DOCTYPE html>
<html lang="en">
<head>

<title><?php echo $app_title; ?>  </title>
<style>
    select#route_end,select#level{
          appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: none !important;
  /*background-color: #fff;*/
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em;
  padding-right: 2.5em;
    }
</style>

</head>
    
   
<?php require_once('../template/phplink.php'); ?>

<script type="text/javascript"  language="javascript">

function update_seat(check_id){  
  
    var t = document.getElementById("training_type").value;
    var inputs = document.querySelectorAll('.sid');
    
    if (t=="Cross Country"){
      for (var i = 0; i < inputs.length; i++) {
        inputs[i].checked = false;
      }
      
      let sid_element =document.getElementById(check_id);
      sid_element.checked=true;
  }
}



function update_training_type(){    

  let var_training_type =document.getElementById('training_type').value;
  let element = document.getElementById("route_end");
 

  //Swal.fire('Information', s,"info");


  if (var_training_type=="Touch and Go"){
    //Swal.fire('Information', var_training_type,"info");
    element.value="RPUI";
    element.disabled = true;
  }else if (var_training_type=="Cross Country"){
    //Swal.fire('Information', var_training_type,"info");
    element.disabled = false;
  }else{
    element.disabled = true;
  }

/*
    var inputs = document.querySelectorAll('.sid');
    if (inputs.length>0){
      for (var i = 0; i < inputs.length; i++) {
        inputs[i].checked = false;
      }
      inputs[0].checked = true;
    }
  */
}


function update_etime_arrival(){  

  var select = document.getElementById('time_departure');
  var time_value = select.options[select.selectedIndex].value;
  var minutes = document.getElementById('training_duration').value;
  time_value= moment(time_value, 'hh:mm A').format('HH:mm');
  
  var parseDate = moment(time_value, 'HH:mm')
    .add(minutes, 'minutes')
    .format('LTS');

    document.getElementById('etime_arrival').value=moment(parseDate, 'hh:mm A').format('HH:mm');

    let element = document.getElementById("level");
    var s=moment(time_value, 'hh:mm A').format('hh:mm A');

    //Swal.fire('Information', s,"info");

    if (s.includes("AM") || s.includes("am")){
      element.value = "IFR";
    }else{
      element.value = "VFR";
    }

}

function update_time_departure(){  
  
  let var_flight_date =document.getElementById('flight_date').value;
  let action="check_time_departure";
    
    //Swal.fire('Information', var_flight_date,"info");
    $.ajax({  
      url:"flight_plan_time_departure.php",  
      method:"POST",  
      dataType:"json",  
      data:{  
        var_flight_date:var_flight_date,
        action:action  
      },  
      success:function(data)  
      { 
        let str_error=data.error;
     
       // Swal.fire('Info!', data.instructor_list_name,'info'); 
        

          if (parseInt(str_error.length)>0){
            if(str_error==" "){
              //Swal.fire('Alert', data.return_list,'info'); 
              $("#time_departure").empty(); // remove old options
              var arr= JSON.parse(data.return_list);

              for(var i=0;i<arr.length;i++)
              {
                var o = new Option(arr[i], arr[i]);
                $('#time_departure').append(o);
              }
              
              //Swal.fire('Alert',data.student_list_name,'info')

              if (data.aircraft_list_name=="" || data.student_list_name=="" || data.instructor_list_name.length==""){
                var x = document.getElementById("btnsubmit");
                var y = document.getElementById("errmsg");
                 x.style.visibility = "hidden";
                 y.style.display = "block"; 
              }else{
                var x = document.getElementById("btnsubmit");
                var y = document.getElementById("errmsg");
                x.style.visibility = "visible";
                y.style.display = "none";
              }

              $("#iaircraft").html(data.aircraft_list_name); // remove old options
              $("#istudent").html(data.student_list_name); // remove old options
              $("#iinstructor").html(data.instructor_list_name); // remove old options
              update_etime_arrival();


            }else{
              Swal.fire('Error Found!', str_error,'error'); 
            }

          }
      } 
    });  
}
         

</script>

<body onload="update_time_departure(); update_etime_arrival();update_training_type();">
<?php require_once('../template/header.php'); ?> 
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">

        <div id="errmsg">
          <?php if ($rsinstructor_total==0 || $rsaircraft_total==0 || $rsstudent_total==0) {
          echo " <span class='alert alert-danger'>One of the following is NOT available. Aircraft or Student or Instructor.</span><br><br>";
          }?>
        </div>

<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1" onsubmit="return validateForm();">

     <div class="form-horizontal">
        <fieldset>
        <div class="row">

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
            <input required onchange="update_time_departure();" type="date" class="form-control"  name="flight_date" id="flight_date" min="<?php echo date('Y-m-d');?>" placeholder=" " value="<?php echo date('Y-m-d');?>">
          </div>

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Time of Departure*</strong></label>
            <select required name="time_departure" id="time_departure" class="form-select" placeholder=" " onchange="update_etime_arrival();">
              <?php foreach ($time_interval as $row_time_interval) {?>
                <option value="<?php echo $row_time_interval;?>"><?php echo $row_time_interval;?></option>
              <?php  }?>
            </select>
          </div>

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Estimated Time of Arrival*</strong></label>
            <input required type="time" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="16:00:00">
          </div>

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
            <input readonly  type="text" class="form-control"  name="route_begin" id="route_begin" placeholder=" " value="RPUI">
          </div>

          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
          <select required name="route_end" id="route_end" class="form-select" placeholder=" ">
            <option value=""></option>
              <?php
                foreach($rsroute as $rs_rowroute) {  
                ?>
                  <option value="<?php echo $rs_rowroute['route_code']?>"><?php echo htmlentities($rs_rowroute['route']);?></option>
                <?php
                  }
                ?>
            </select>
          </div>

          <!--<div class="form-group col-md-2 col-sm-12">-->
          <!--<label class="col-sm-12 col-form-label"><strong>Level</strong></label>-->
          <!--  <select readonly required name="level" id="level" class="form-select" placeholder=" ">-->
          <!--    <?php-->
          <!--      foreach($rslevel as $rs_rowlevel) {  -->
          <!--      ?>-->
          <!--        <option value="<?php echo $rs_rowlevel['level']?>"><?php echo htmlentities($rs_rowlevel['level']);?></option>-->
          <!--      <?php-->
          <!--        }-->
          <!--      ?>-->
          <!--  </select>-->
          <!--</div>-->
<div class="form-group col-md-4 col-sm-12">
  <label class="col-sm-12 col-form-label"><strong>Level</strong></label>
  <select disabled required name="level" id="level" class="form-select" placeholder=" ">
    <?php
    foreach ($rslevel as $rs_rowlevel) {
      ?>
      <option value="<?php echo $rs_rowlevel['level'] ?>"><?php echo htmlentities($rs_rowlevel['level']); ?></option>
      <?php
    }
    ?>
  </select>
  <input type="hidden" name="level" value="<?php echo $rs_rowlevel['level']; ?>">
</div>

        </div>
        <br>
        <div class="row">
          <div class="form-group col-md-4 col-sm-12">
          <label class="col-sm-12 col-form-label"><strong>Type of Training*</strong></label>
          
            <select  required name="training_type" id="training_type" class="form-select" placeholder=" " onchange="update_training_type();">
            <option value="" >Select type of training</option>
              <option value="Touch and Go" >Touch and Go</option>
              <option value="Cross Country">Cross Country</option>
            </select>
          </div>

          <div class="form-group col-md-8 col-sm-12">
            <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
              <input type="text" class="form-control"  name="note" id="note" placeholder=" " value="">
            </div>
          </div>
        </div>
        <br>
          
        <div class="row">
       
          <div class="form-group col-md-4 col-sm-12">
              <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
              <div class="col-sm-12 row">
              <div id="iaircraft">
              <?php $firsttime=true; foreach ($rsaircraft as $rs_rowaircraft){ ?>
                <div class="form-check form-switch col-sm-12"> <input required  class="form-check-input" type="radio" id="aid<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>" <?php if ($firsttime==true) echo 'checked'; $firsttime=false; ?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
              <?php } ?>
              </div>
              </div>
          </div>

   
            <div class="form-group col-md-4 col-sm-12">
                <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
                <div class="col-sm-12 row">
                  <div id="istudent">
                    <?php $firsttime=true; foreach ($rsstudent as $rs_rowstudent){ ?>
                      <div class="form-check form-switch col-sm-12"><input  class="form-check-input sid" type="radio" value="<?php echo htmlentities($rs_rowstudent['id']);?>" onchange="update_seat('sid<?php echo htmlentities($rs_rowstudent['id']);?>')" id="sid<?php echo htmlentities($rs_rowstudent['id']);?>" name="sid[]" <?php if ($firsttime==true) echo 'checked'; $firsttime=false; ?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
                    <?php } ?>
                  </div>
                </div>
            </div>
       

          <div class="form-group col-md-4 col-sm-12">
              <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
              <div class="col-sm-12 row">
                <div id="iinstructor">
                  <?php $firsttime=true; foreach ($rsinstructor as $rs_rowinstructor){ ?>
                    <div class="form-check form-switch col-sm-12"> <input required   class="form-check-input" type="radio" id="iid<?php echo htmlentities($rs_rowinstructor['id']);?>" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>" <?php if ($firsttime==true) echo 'checked'; $firsttime=false; ?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
                  <?php } ?>
                </div>
              </div>
          </div>
              
        </div>

        <br>
       
        <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
        
       
            <button id="btnsubmit"  type="submit" class="btn btn-outline-primary" form="form1" id="save"><span class="bi-save"></span> Save</button>
           <a href="flight_plan_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a> 
           
          </div>
        </div>

    </fieldset>  
  </div>
<input type="hidden" name="POSTcheck" value="form1">
<input type="hidden" name="slug" id="slug" value="<?php echo $slug; ?>">
<input type="hidden" name="inspection_status"  id="inspection_status" value="for inspection">
<input type="hidden" name="training_duration" id="training_duration" value="<?php echo $rsbasic['training_duration'];?>">
</form>

<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>
<?php require_once('../template/footer.php'); ?>
<script>


</body>
</html>
<?php
$db->close();
?>
