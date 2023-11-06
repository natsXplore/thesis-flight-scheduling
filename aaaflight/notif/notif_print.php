
<?php require_once('../template/header_print.php');?>
<div align="center">
  <strong><?php echo strtoupper(htmlentities($_SESSION['title'])); ?></strong><br>
  <?php echo 'Date Printed : '.date('F d, Y');?>

<table id="tablelist_print" class="table table-bordered" width="100%">
  <thead >
  <tr class="alert-info">
    <th>#</th>
    <th>Notification</th>
    <th>Date Posted</th>
  </tr>
  </thead>
  <tbody>
  <?php $ctr=0; foreach ($rs as $rs_data){ $ctr++;?>
    <tr>
      <td class="align-middle"><?php echo $ctr; ?>.</td>
      <td class="align-middle"><?php echo htmlentities($rs_data['notification']); ?></td>
      <td class="align-middle"><?php echo htmlentities($rs_data['date_created']); ?></td>
    </tr>
  <?php } ?>
  </tbody>
</table>
</div>
<?php require_once('../template/footer_print.php');?>
