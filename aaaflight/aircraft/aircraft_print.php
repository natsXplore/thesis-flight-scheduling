
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

<table id="tablelist_print" class="table table-bordered" width="100%">
  <thead >
  <tr class="alert-info">
    <th>#</th>
    <th>Aircraft ID</th>
    <th>Type</th>
    <th>Seat</th>
    <th>Validity</th>
  </tr>
  </thead>
  <tbody>
  <?php $ctr=0; foreach ($rs as $rs_data){ $ctr++;?>
    <tr>
      <td class="align-middle"><?php echo $ctr; ?>.</td>
      <td class="align-middle"><?php echo htmlentities($rs_data['aircraft_id']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_data['type_of_aircraft']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_data['seat']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_data['date_validity']); ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
