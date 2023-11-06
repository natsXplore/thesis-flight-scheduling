<?php ob_start(); ?><?php require_once('../connections/pdoconnect.php'); ?>
<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));

$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
  $query_rsparent_id = "SELECT * FROM user_menu ORDER BY `icon` ASC";
  $db->query($query_rsparent_id);
  $rsparent_menu = $db->rowsingle();
  $rsparent_menu_total=$db->rowcount();

  if ($rsparent_menu_total>0) {$breadcrumbs=htmlentities($rsparent_menu['Name'].' / '.$_POST['name']);}
  else {$breadcrumbs=htmlentities($_POST['name']);}

    $SQLcrud = "UPDATE `user_menu` SET `parent_id`=?, `name`=?, `href`=?, `icon`=?, `type`=?, `order`=?, `description`=?, `breadcrumbs`=? WHERE id=?";
  
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['parent_id']));
    $db->bind(2,htmlentities($_POST['name']));
    $db->bind(3,htmlentities($_POST['href']));
    $db->bind(4,htmlentities($_POST['icon']));
    $db->bind(5,htmlentities($_POST['type']));
    $db->bind(6,htmlentities($_POST['order']));
    $db->bind(7,htmlentities(str_replace(" ","_",strtolower($_POST['name']))));
    $db->bind(8,$breadcrumbs);
    $db->bind(9,$_POST['id']);
    $db->execute();

    $GoTo = "user_menu_list.php";
    header(sprintf("Location: %s", $GoTo));

}
  
$query_rs = "SELECT * FROM `user_menu` WHERE id = ?";
$db->query($query_rs);
$db->bind(1,htmlentities($_GET['recordID']));
$rs_data = $db->rowsingle();

$query_rscombo = "SELECT * FROM user_icon ORDER BY `icon` ASC";
$db->query($query_rscombo);
$rsicon = $db->rowset();
$totalRows_rsicon = $db->rowcount();

$query_rscombo = "SELECT *  FROM `user_menu` where `type`='m' and (id=parent_id) order by `order`,`parent_id`"; 
$db->query($query_rscombo);
$rsmenu = $db->rowset();
$totalRows_rsmenu = $db->rowcount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">
<title><?php echo $app_title; ?>  </title>
</head>
    
<script type="text/javascript"  language="javascript">
    function validateForm(){
        var x = document.forms["form1"]["results_here1"].value;
        if (x == null || x == "") { return true; }
        else { document.getElementById("results_here").innerHTML ="Record NOT Save. Duplicate Record Found!";document.getElementById("name").focus();return false; }
    }

    function setIcon(iconname){
    document.getElementById("icon").value=iconname;
  }
</script>

<?php require_once('../template/phplink.php'); ?>

<!--
<script type="text/javascript">
 $(document).ready(function () {
  $('#Date').datepicker({format: "yyyy-mm-dd",autoclose:true}); /*input ID*/

});
</script>
-->
<body>
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form  method="POST" id="form1" name="form1" onsubmit="return validateForm();">
  
    <div class="form-horizontal">
    <fieldset>    
    
      <span id="results_here" class="alert-danger"></span><input type="hidden" id="results_here1">
      <div class="form-group">
        <label for="">Name</label>
        <input class="form-control" type="text" name="name" id="name"
              value="<?php if (isset($_POST['name'])) echo htmlentities($_POST['name']); else echo htmlentities($rs_data['name']); ?>" size="32"
              OnKeyUp="showAjax('user_menu_duplicate.php','txtString',this.value + '&prev_id=<?php if (isset($_POST['prev_id'])) echo htmlentities($_POST['prev_id']); else echo htmlentities($rs_data['name']);?>' , 'results_here');">
      </div>
<br>
        <div class="form-group">
            <!-- Text input-->
            <label for="">Link</label>
                <input name="href" id="href"  type="text" class="form-control" value="<?php echo htmlentities($rs_data['href']); ?>" size="32">
        </div>
        <br>
         <div class="form-group">
          <!-- Text input-->
          <label for="">Parent Menu</label>
            <select name="parent_id" id="parent_id" class="form-select ">
              <option value=""></option>
                <?php
                    foreach($rsmenu as $row_rsmenu) {
                ?>
                <option value="<?php echo htmlentities($row_rsmenu['id'])?>"
                <?php if ( !(strcmp(htmlentities($row_rsmenu['id']), htmlentities($rs_data['parent_id'])))){echo "selected=\"selected\"";}?>>
                <?php echo htmlentities($row_rsmenu['name']);?></option>


              <?php

                $query_rssub = "SELECT * FROM `user_menu` WHERE `parent_id` LIKE ? and `id`!=`parent_id` and `type`='m' order by `order`,id";
                $db->query($query_rssub);
                $db->bind(1,$row_rsmenu['id']);
                $rssub=$db->rowset();
                $totalRows_rssub=$db->rowcount();
              ?>
                <?php if ($totalRows_rssub>0) { foreach ($rssub as $rssub_data){ ?>
                   <option value="<?php echo htmlentities($rssub_data['id'])?>"
                <?php if (!(strcmp(htmlentities($rssub_data['id']), htmlentities($rs_data['parent_id'])))){echo "selected=\"selected\"";}?>>
                <?php echo '&nbsp;&nbsp;&nbsp;'.htmlentities($rssub_data['name']);?></option>

                <?php
                  }}}
                ?>
              </select>
        </div>
        <br>
        <div class="form-group">
            <label for="">Icon</label>
            <div class="input-group"><span class="input-group-text" id="inputGroupPrepend">
              <i class="bi bi-gem"  data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"  data-toogle="tooltip" data-placement="bottom" title="Choose ICON"> </i></span>
              <input type="text" name="icon" id="icon" class="form-control col-md-6 col-sm-12" value="<?php echo $rs_data['icon']; ?>">
                </div>
              
              <div class="modal fade" id="modalDialogScrollable" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"><strong>ICON List</strong></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="iconslist">
                      <?php
                      $icon="";
                        foreach($rsicon as $row_rsicon) {  
                          $icon.='<button type="button" onclick="setIcon(\''.htmlentities($row_rsicon['icon']).'\')">';
                          $icon.='<div class="icon">';
                          $icon.='<i class="'.htmlentities($row_rsicon['icon']).'"></i>';
                          $icon.='<div class="label">'.htmlentities($row_rsicon['icon']).'</div>';
                          $icon.='</div>';
                          $icon.='</button>';
                          echo $icon;  
                          $icon="";
                        } ?>
                      </div>
                     </div>
                  <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
                  
                </div>
              </div>
        </div>
        <br>
        <div class="form-group">
            <label for="">Type</label>
                <select  name="type" id="type" class="form-select ">
                    <option value="m"
                        <?php if (!(strcmp("m", htmlentities($rs_data['type'])))){echo "selected=\"selected\"";}?>>Main Menu</option>
                    <option value=""
                        <?php if (!(strcmp("", htmlentities($rs_data['type'])))){echo "selected=\"selected\"";}?>></option>

                </select>
        </div>
        <br>
        <div class="form-group">
          <label for="">Order</label>
          <input class="form-control" type="text" name="order" id="order"
              value="<?php if (isset($_POST['order'])) echo htmlentities($_POST['order']); else echo htmlentities($rs_data['order']); ?>" size="32">
        </div>

<br>
        <div class="form-group">
          <div class="col-md-2"></div>
          <div class="col-md-10">
              <button type="submit" class="btn btn-outline-primary" form="form1"><span class="bi-save"></span> Save</button>
          <a href="user_menu_list.php" class="btn btn-outline-danger"><span class="bi-x-octagon"></span> Cancel</a>
          </div>
        </div>

    </fieldset>    
    </div> 
    
  <input type="hidden" name="POSTcheck" value="form1">
  <input type="hidden" name="id" value="<?php echo htmlentities($rs_data['id']); ?>">
  
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
