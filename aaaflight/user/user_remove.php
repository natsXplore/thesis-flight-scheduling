<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

    $SQLcrud = "DELETE FROM user WHERE id = ?";
    $db->query($SQLcrud);
    $db->bind(1,htmlentities($_POST['id']));
    $db->execute();
  
   unlink("../images/user/".$_POST['id'].".jpg");

    $GoTo = "user_list.php";
    header(sprintf("Location: %s", $GoTo));
}

$recordID = "-1";
if (isset($_GET['recordID'])) {
  $recordID = htmlentities($_GET['recordID']);
}
$query_rs = "SELECT * FROM `user` u WHERE u.id = ?";
$db->query($query_rs);
$db->bind(1,$recordID);
$row_rs = $db->rowsingle();

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
<?php require_once('../template/phplink.php'); ?>


<body>
<?php require_once('../template/header.php'); ?>
  <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form method="post" name="form1" id="form1" >
      <div class="form-horizontal">
        <fieldset>
        
        <div class="form-group" align="center">
         <img id="image_preview1" src="../images/user/<?php echo htmlentities($row_rs['id']); ?>.jpg" class="img-fluid" width="400px" height="400px"/>
            <div class="mb-4">
                <label for="image-upload1" class="form-label">Upload Photo  (size : 400x400 | Type: jpg)</label>
                <input class="form-control" type="file" id="image-upload1" name="image-upload1" onchange="preview1()" accept="image/jpg">
               
            </div> 
       </div>
      <br>
      <div class="row">

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="firstname" id="firstname" placeholder=" " value="<?php echo htmlentities($row_rs['firstname']); ?>"><label >First Name*</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="middlename" id="middlename" placeholder=" " value="<?php echo htmlentities($row_rs['middlename']); ?>"><label >Middle Name</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="lastname" id="lastname" placeholder=" " value="<?php echo htmlentities($row_rs['lastname']); ?>"><label >Last Name*</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="extname" id="extname" placeholder=" " value="<?php echo htmlentities($row_rs['extname']); ?>"><label >Ext. Name</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder=" " value="<?php echo htmlentities($row_rs['date_of_birth']); ?>" onblur="calculateAge();" onclick="calculateAge();"><label >Date of Birth*</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="age" id="age" placeholder=" " value="<?php echo htmlentities($row_rs['age']); ?>"><label >Age</label></div>
        </div>

        </div>
        <br>
        <div class="row">

        <div class="form-group col-md-6 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder=" " value="<?php echo htmlentities($row_rs['place_of_birth']); ?>"><label >Place of Birth*</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="nationality" id="nationality" placeholder=" " value="<?php echo htmlentities($row_rs['nationality']); ?>"><label >Nationality*</label></div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
                <input readonly type="text" class="form-control col-form-label"  name="gender" id="gender" placeholder=" " value="<?php echo htmlentities($row_rs['gender']); ?>">
                <label>Gender*</label>
            </div>

        </div>

        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating"><input readonly type="text" class="form-control" name="contact_no" id="contact_no" placeholder=" " value="<?php echo htmlentities($row_rs['contact_no']); ?>"><label >Contact No*</label></div>
        </div>

        </div>
        <br>
        <div class="form-group">
        <div class="form-floating ">
            <input required type="text" class="form-control col-form-label"  name="address" id="address" placeholder=" " value="<?php echo htmlentities($row_rs['address']); ?>">
            <label >Address</label>
        </div>
        </div>

        <br>
        <div class="row">
        <div class="form-group col-md-2 col-sm-12">
        <div class="form-floating">
            <input readonly type="text" class="form-control col-form-label"  name="designation" id="designation" placeholder=" " value="<?php echo htmlentities($row_rs['designation']); ?>">
            <label for="designation">Designation*</label>
            </div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
            <input readonly type="text" class="form-control col-form-label"  name="blood_type" id="blood_type" placeholder=" " value="<?php echo htmlentities($row_rs['blood_type']); ?>">
                <label>Blood Type</label>
            </div>
        </div>

        <div class="form-group col-md-5 col-sm-12">
        <div class="form-floating ">
            <input required type="text" class="form-control col-form-label"  name="contact_person" id="contact_person" placeholder=" " value="<?php echo htmlentities($row_rs['contact_person']); ?>">
            <label >Contact Person*</label>
        </div>
        </div>

        <div class="form-group col-md-3 col-sm-12">
        <div class="form-floating ">
            <input required type="text" class="form-control col-form-label"  name="contact_person_no" id="contact_person_no" placeholder=" " value="<?php echo htmlentities($row_rs['contact_person_no']); ?>">
            <label >Contact No*</label>
        </div>
        </div>
            
        </div>
                
        <br>    
        <div class="row">
        <div class="form-group col-md-4 col-sm-12">
                <span id="results_here" class="alert-danger"></span> <input type="hidden" id="results_here1">
                <div class="form-floating" >
                    <input required class="form-control" type="email" name="username" id="username"  placeholder="Enter Email"
                    value="<?php echo htmlentities($row_rs['username']); ?>" size="32"  placeholder=" " OnKeyUp="showAjax('user_duplicate.php','txtString',this.value + '&prev_id=<?php if (isset($_POST['prev_id'])) echo $_POST['prev_id']; else echo htmlentities($row_rs['username']);?>' , 'results_here');" Onblur="showAjax('user_duplicate.php','txtString',this.value + '&prev_id=<?php if (isset($_POST['prev_id'])) echo $_POST['prev_id']; else echo htmlentities($row_rs['username']);?>' , 'results_here');">
                    <label>Email/UserName</label>
                </div>
        </div>

        <div class="form-group col-md-2 col-sm-12">

                <div id="pw_error" id="pw_error" class="alert-danger"></div>
                <div class="form-floating">
                    
                    <input required name="passw" type="password" class="form-control" id="passw" value="<?php echo $phu->decryptMessage(htmlentities($row_rs['password'])); ?>"  placeholder=" ">
                    <label>Password</label>
                </div>
                <input class="form-check-input" type="checkbox" id="gridCheck1" onclick="showhidepassword()"> <label class="form-check-label" for="gridCheck1">Show Password</label>
        </div>

        <div class="form-group col-md-2 col-sm-12">
                
                <div class="form-floating">
                    <input class="form-control" type="password" name="ReType" id="ReType" value="" size="32"  placeholder=" ">
                    <label>Re-Type Password</label>
                </div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="text" class="form-control" name="group" id="group" placeholder=" " value="<?php echo htmlentities($row_rs['group']); ?>">
                <label>User Group</label>
                </div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
                <input readonly type="text" class="form-control" name="status" id="status" placeholder=" " value="<?php echo htmlentities($row_rs['status']); ?>">
                <label>Status</label>
            </div>
        </div>
        </div>
        <br>

        <div class="row">      
            <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input readonly type="date" class="form-control" name="fil" id="fil" placeholder=" " value="<?php echo htmlentities($row_rs['fil']); ?>"
            ><label >Flight Instructor License</label></div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="date" class="form-control" name="cpl" id="cpl" placeholder=" " value="<?php echo htmlentities($row_rs['cpl']); ?>"
                ><label >Commercial Pilot License</label></div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="date" class="form-control" name="ntc" id="ntc" placeholder=" " value="<?php echo htmlentities($row_rs['ntc']); ?>"
                ><label >NTC </label></div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="date" class="form-control" name="medl" id="medl" placeholder=" " value="<?php echo htmlentities($row_rs['medl']); ?>"
                ><label >Medical License</label></div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="date" class="form-control" name="elp" id="elp" placeholder=" " value="<?php echo htmlentities($row_rs['elp']); ?>"
                ><label >English Proficiency License</label></div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="date" class="form-control" name="mecl" id="mecl" placeholder=" " value="<?php echo htmlentities($row_rs['mecl']); ?>"
                ><label >Mechanic License</label></div>
            </div>

        </div>
        
    
      <br>
      <div class="row">      
              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input readonly type="number" min="0" class="form-control" name="required_hours" id="required_hours" placeholder=" " value="<?php echo htmlentities($row_rs['required_hours']); ?>"
                ><label >Required Hours</label></div>
              </div>

             
            </div>
          <br>

          <div class="form-group">
                <label class="col-md-2 control-label" align="right"></label>
                <div class="form-floating input-group">

                 Are you sure you want to Remove this Record? &nbsp;<button type="submit" class="btn btn-outline-danger" form="form1"><span class="bi-trash"></span> Yes</button>
                 <a href="user_list.php" class="btn btn-outline-primary hidelink"><span class="bi-x-octagon"></span> No</a>
                </div>
          </div>


    <input type="hidden" name="POSTcheck" value="form1">
    <input type="hidden" name="id" id="id" value="<?php echo htmlentities($row_rs['id']);?>">  
    </fieldset>
    </div>  
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
