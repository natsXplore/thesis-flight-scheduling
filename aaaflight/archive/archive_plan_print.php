
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

  <table id="tablelist" class="table table-striped table-hover table-responsive table-bordered" >
    <thead>
    <tr class="alert-info">
            <th>Aircraft ID</th>
            <th>Aircraft Validity</th>
            <th>Flight Date</th>
            <th>Departure Time</th>
            <th>Estimated Time of Arrival</th>
            <th>Pilot in Command</th>
            <th>Route</th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

        $query_rs = "SELECT * FROM `flight_sched_passenger_archive` fsp INNER JOIN user u ON u.id=fsp.user_id INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE `flight_sched_id`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$rs_data['flight_sched_id']);
        $db->bind(2,'Instructor');
        $rscount_instructor=$db->rowsingle();
        $rscount_instructor_total=$db->rowcount();
        ?>
        <tr >
        <td class="align-middle"><?php echo htmlentities($rscount_instructor['aircraft_id']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rscount_instructor['date_validity']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['etime_arrival']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rscount_instructor['firstname']).' '.htmlentities($rscount_instructor['lastname']).' '.htmlentities($rscount_instructor['extname']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['route_begin']).'-'.htmlentities($rs_data['route_end']); ?></td>
   
    </tr>
<?php } ?>
</tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
