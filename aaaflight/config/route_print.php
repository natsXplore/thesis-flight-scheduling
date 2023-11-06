
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

<table id="tablelist_print" class="table table-bordered" width="100%">
  <thead >
  <tr class="alert-info">
    <th>#</th>
    <th>Route Code</th>
    <th>Route</th>
  </tr>
  </thead>
  <tbody>
  <?php $ctr=0; foreach ($rs as $rs_data){ $ctr++;?>
    <tr>
      <td class="align-middle"><?php echo $ctr; ?>.</td>
      <td class="align-middle"><?php echo htmlentities($rs_data['route_code']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_data['route']); ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
