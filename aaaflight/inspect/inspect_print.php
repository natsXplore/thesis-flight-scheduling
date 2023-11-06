
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
            <th>Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($rs as $rs_data){ 

        $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE `flight_sched_id`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$rs_data['flight_sched_id']);
        $db->bind(2,'Instructor');
        $rscount_instuctor=$db->rowsingle();
        $rscount_instructor_total=$db->rowcount();
        ?>
        <tr >
        <td class="align-middle"><?php echo htmlentities($rscount_instuctor['aircraft_id']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rscount_instuctor['date_validity']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['flight_date']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['time_departure']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['etime_arrival']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rscount_instuctor['firstname']).' '.htmlentities($rscount_instuctor['lastname']).' '.htmlentities($rscount_instuctor['extname']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['inspection_type']); ?></td>
        <td class="align-middle"><?php echo htmlentities($rs_data['inspection_status']); ?></td>
   
    </tr>
<?php } ?>
</tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
