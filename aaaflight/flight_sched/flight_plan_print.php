
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

  <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
    <thead>
    <tr class="alert-info">
    <th>Aircraft ID / Validity</th>
            <th>Flight Date</th>
            <th>Departure - Arrival</th>
            <th>Pilot in Command</th>
            <th>Route</th>
            <th>Type</th>
            <th>Aircraft Status</th>
            <th>Flight Status</th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

$query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE `flight_sched_id`=? AND u.group=?";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$db->bind(2,'Instructor');
$rscount_instructor=$db->rowsingle();
$rscount_instructor_total=$db->rowcount();

$istatus="";
$query_rs = "SELECT * FROM `inspection_sched` WHERE `flight_sched_id`=?";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$rsinspect=$db->rowsingle();
$rsinspect_total=$db->rowcount();
if ($rsinspect_total>0){
  $istatus=$rsinspect['inspection_status'];
}

$query_rs = "SELECT * FROM `inspection_sched` WHERE `flight_sched_id`=? AND inspection_type='PRE-FLIGHT'";
$db->query($query_rs);
$db->bind(1,$rs_data['flight_sched_id']);
$rsinspect_pre=$db->rowsingle();


?>
<tr >
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['aircraft_id']).'<br>'.htmlentities($rscount_instructor['date_validity']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']).' - '.htmlentities($rs_data['etime_arrival']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rscount_instructor['firstname']).' '.htmlentities($rscount_instructor['lastname']).' '.htmlentities($rscount_instructor['extname']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['departure_aerodome']).'-'.htmlentities($rs_data['destination_aerodome']); ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['training_type']); ?></td>
    <td class="align-middle" <?php if ($istatus=="NOT Ready for Flight") echo "style='background-color:red;'"?>><?php echo $istatus; ?></td>
    <td class="align-middle"><?php echo htmlentities($rs_data['flight_status']); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
