

<?php require_once('../template/header_print.php');?>

<style type = "text/css">

      @page {
         size: auto;   /* auto is the initial value */
         margin: 0.25cm;
      }


</style>

<div class="row">
  <div align="center">    
    <strong>FLIGHT PLAN VIEW Report</strong><br>
    <?php echo 'Date Printed : '.date('F d, Y');?>
  </div>

  <br>


  <div class="row">

    <div class="form-group col-md-4 col-sm-4">
    <label class="col-sm-12 col-form-label"><strong>Flight Date*</strong></label>
      <input  style="font-size:11px;" disabled type="date" class="form-control"  name="flight_date" id="flight_date" placeholder=" " value="<?php echo htmlentities($rs_data['flight_date']);?>">
    </div>

    <div class="form-group col-md-4 col-sm-4">
    <label class="col-sm-12 col-form-label"><strong>Time of Departure*</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="time_departure" id="time_departure" placeholder=" " value=<?php echo htmlentities($rs_data['time_departure']);?>>
    </div>

    <div class="form-group col-md-4 col-sm-4">
    <label class="col-sm-12 col-form-label"><strong>Estimated Time of Arrival*</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="etime_arrival" id="etime_arrival" placeholder=" " value="<?php echo htmlentities($rs_data['etime_arrival']);?>">
    </div>

  </div>
  <br>
  <div class="row">

    <div class="form-group col-md-4 col-sm-4">
      <label class="col-sm-12 col-form-label"><strong>Departure Aerodome*</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="departure_aerodome" id="departure_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['departure_aerodome']);?>">
    </div>

    <div class="form-group col-md-4 col-sm-4">
      <label class="col-sm-12 col-form-label"><strong>Destination Aerodome*</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="destination_aerodome" id="destination_aerodome" placeholder=" " value="<?php echo htmlentities($rs_data['destination_aerodome']);?>">
    </div>

    <div class="form-group col-md-4 col-sm-4">
      <label class="col-sm-12 col-form-label"><strong>Level</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="level" id="level" placeholder=" " value="<?php echo htmlentities($rs_data['level']);?>">
    </div>

  </div>

  <br>
  <div class="row">

    <div class="form-group col-md-6 col-sm-6">
      <label class="col-sm-12 col-form-label"><strong>Training Type</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="training_type" id="training_type" placeholder=" " value="<?php echo htmlentities($rs_data['training_type']);?>">
    </div>

    <div class="form-group col-md-6 col-sm-6">
      <label class="col-sm-12 col-form-label"><strong>Flight Status</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="flight_status" id="flight_status" placeholder=" " value="<?php echo htmlentities($rs_data['flight_status']);?>">
    </div>

    </div>
    <br>
    <div class="row">
    <div class="form-group col-md-12 col-sm-12">
    <label class="col-sm-12 col-form-label"><strong>Remarks</strong></label>
      <input  style="font-size:11px;" disabled type="text" class="form-control"  name="note" id="note" placeholder=" " value="<?php echo htmlentities($rs_data['note']);?>">
    </div>
    </div>
  
  <br>

  <div class="row">

    <div class="form-group col-md-4 col-sm-4">
        <label class="col-sm-12 col-form-label"><strong>Aircraft Identification</strong></label>
        <div class="col-sm-12">
        <?php foreach ($rsaircraft as $rs_rowaircraft){ 
          if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1){
          ?>
          <div class="form-check form-switch col-sm-12"> <input  style="font-size:11px;"  disabled onchange="update_aircraft('<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>');" class="form-check-input" type="radio" id="aid" name="aid" value="<?php echo htmlentities($rs_rowaircraft['lup_aircraft_id']);?>"  <?php if ($phu->get_flight_sched_aircraft(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowaircraft['lup_aircraft_id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowaircraft['aircraft_id']);?></label></div>
        <?php } }?>
        </div>
    </div>


      <div class="form-group col-md-4 col-sm-4">
          <label class="col-sm-12 col-form-label"><strong>Student</strong></label>
          <div class="col-sm-12">
          <?php foreach ($rsstudent as $rs_rowstudent){ 
            if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1){
            ?>
            <div class="form-check form-switch col-sm-12"> <input  style="font-size:11px;"  disabled onchange="update_student('<?php echo 's'.htmlentities($rs_rowstudent['id']);?>');" class="form-check-input" type="checkbox" value="<?php echo htmlentities($rs_rowstudent['id']);?>" id="sid" name="sid" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowstudent['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowstudent['firstname']).' '.htmlentities($rs_rowstudent['lastname']).' '.htmlentities($rs_rowstudent['extname']);?></label></div>
          <?php } }?>
          </div>
      </div>


    <div class="form-group col-md-4 col-sm-4">
        <label class="col-sm-12 col-form-label"><strong>Instructor</strong></label>
        <div class="col-sm-12">
        <?php foreach ($rsinstructor as $rs_rowinstructor){ 
          if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1) {?>
          <div class="form-check form-switch col-sm-12"> <input  style="font-size:11px;"  disabled onchange="update_instructor('<?php echo htmlentities($rs_rowinstructor['id']);?>');" class="form-check-input" type="radio" id="iid" name="iid" value="<?php echo htmlentities($rs_rowinstructor['id']);?>" <?php if ($phu->get_flight_sched_user(htmlentities($rs_data['flight_sched_id']),htmlentities($rs_rowinstructor['id']))==1)  echo "checked";?>> <label class="form-check-label" for=""><?php echo htmlentities($rs_rowinstructor['firstname']).' '.htmlentities($rs_rowinstructor['lastname']).' '.htmlentities($rs_rowinstructor['extname']);?></label></div>
        <?php } }?>
        </div>
    </div>
      
  </div>



  <?php if ($rs_data['training_type']=="Touch and Go"){ ?>
  <div class="row" style="font-size:11px;margin: 0.25cm;">
    <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
        <thead>
          <tr class="alert-success">
              <th colspan="3" class="alert-danger" class="align-middle">ESTIMATED TIME</th>
              <th colspan="6" class="alert-success" class="align-middle">ACTUAL TIME</th>
            </tr>

            <tr class="alert-info">
                <th class="alert-danger">Date</th>
                <th class="alert-danger">Landing</th>
                <th class="alert-danger">Take-Off</th>
                

                <th>Date</th>
                <th>Landing</th>
                <th>Take-Off</th>
                <th>Total Time</th>
                
            </tr>
        </thead>
        <tbody>

    <?php $remove_ctr=0; 

    $estimate_time=$rs_data['time_departure'];
    $estimate_landing="";
    $estimate_takeoff="";


    foreach ($rstouch as $rs_rstouch){ 

      //echo $estimate_time.'-';
      //echo strtotime($rs_data['etime_arrival']);


      //$estimate_time=strtotime(date('H:i:s'));
      if (htmlentities($rs_rstouch['landing'])=="HANGAR"){
        $estimate_landing="HANGAR";
        $estimate_takeoff=$estimate_time;
        $estimate_time=$estimate_takeoff;

      }else{
        $estimate_landing=date('H:i:s', strtotime('+7 minutes', strtotime($estimate_time)));
        $estimate_takeoff=$estimate_landing;
        $estimate_time=$estimate_landing;
      }

      
      if (htmlentities($rs_rstouch['takeoff'])=="HANGAR"){
        $estimate_takeoff="HANGAR";
      }

      $total_time_check="";

      if ($rs_rstouch['total_time']=="00:00"){
        $total_time_check="";
      }else{
        $total_time_check=$rs_rstouch['total_time'];
      }

    //   if ($estimate_takeoff=="HANGAR"){
    //     $total_time_check='<strong>'.$rstouch_time["ttime"].'</strong>';
    //   }
    if ($estimate_takeoff == "HANGAR") {
    $total_time_check = '<strong>' . substr($rstouch_time["ttime"], 0, 8) . '</strong>';
}


      ?>
        <tr >
        <td class="align-middle" class="alert-danger"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
        <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_landing); ?></td>
        <td class="align-middle" class="alert-danger"><?php echo htmlentities($estimate_takeoff); ?></td>

        <td class="align-middle"><?php echo htmlentities($rs_rstouch['flight_date']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_rstouch['landing']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_rstouch['takeoff']); ?></td>
        <td class="align-middle"><?php echo $total_time_check; ?></td>

      
        </tr>
    <?php $remove_ctr++;} ?>



    </tbody>
    </table>
  </div>
  <?php } ?>

</div>




<br><br><br>
<div class="row">
<?php require_once('../template/footer_print.php');?>
</div>
