<?php ob_start(); ?><?php require_once('../connections/pdoconnect.php'); ?>
<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db= new DatabaseConnect();

$totalRows_rscheck=0;

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {

  $SQLcrud = "UPDATE user SET `firstname`=?, `middlename`=?, `lastname`=?, `extname`=?, `date_of_birth`=?, `age`=?, `place_of_birth`=?, `nationality`=?, `gender`=?, `contact_no`=?, `address`=?, `blood_type`=?, `contact_person`=?, `contact_person_no`=?, `designation`=?, `username`=?, `fil`=?, `cpl`=?, `ntc`=?, `medl`=?, `elp`=?, `mecl`=?, `password`=?, `group`=? WHERE id=?";
  $db->query($SQLcrud);
  $db->bind(1,htmlentities($_POST['firstname']));
  $db->bind(2,htmlentities($_POST['middlename']));
  $db->bind(3,htmlentities($_POST['lastname']));
  $db->bind(4,htmlentities($_POST['extname']));
  $db->bind(5,htmlentities($_POST['date_of_birth']));
  $db->bind(6,htmlentities($_POST['age']));
  $db->bind(7,htmlentities($_POST['place_of_birth']));
  $db->bind(8,htmlentities($_POST['nationality']));
  $db->bind(9,htmlentities($_POST['gender']));
  $db->bind(10,htmlentities($_POST['contact_no']));
  $db->bind(11,htmlentities($_POST['address']));
  $db->bind(12,htmlentities($_POST['blood_type']));
  $db->bind(13,htmlentities($_POST['contact_person']));
  $db->bind(14,htmlentities($_POST['contact_person_no']));
  $db->bind(15,htmlentities($_POST['designation']));
  $db->bind(16,htmlentities($_POST['username']));
  $db->bind(17,htmlentities($_POST['fil']));
  $db->bind(18,htmlentities($_POST['cpl']));
  $db->bind(19,htmlentities($_POST['ntc']));
  $db->bind(20,htmlentities($_POST['medl']));
  $db->bind(21,htmlentities($_POST['elp']));
  $db->bind(22,htmlentities($_POST['mecl']));
  $db->bind(23,htmlentities($phu->encryptMessage($_POST['password'])));
  $db->bind(24,htmlentities($_POST['group']));
  $db->bind(25,htmlentities($_POST['id']));
  $db->execute();
	
    $allowed = array('jpg');
    $filenames="../images/user/". $_POST['id'].".jpg";

    if(isset($_FILES['image-upload1']) && $_FILES['image-upload1']['error'] == 0){

      $extension = pathinfo($_FILES['image-upload1']['name'], PATHINFO_EXTENSION);


      if(move_uploaded_file($_FILES['image-upload1']['tmp_name'], $filenames)){  

      }
    }


		$updateGoTo = "../admin/index.php";
		header(sprintf("Location: %s", $updateGoTo));

}

$query_rsdesignation = "SELECT * FROM designation ORDER BY `designation` ASC";
$db->query($query_rsdesignation);
$rsdesignation = $db->rowset();
$totalRows_rsdesignation = $db->rowcount();

$query_rsposition = "SELECT * FROM user WHERE id = ?";
$db->query($query_rsposition);
$db->bind(1,$_SESSION['MM_ID']);
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
<script type="text/javascript"  language="javascript">
    function validateForm(){

        var spw =  document.forms["form1"]["password"].value;
        var srepw =  document.forms["form1"]["ReType"].value;
        if (spw != srepw){
            document.getElementById("pw_error").innerHTML ="Password did NOT match!";return false;}
        else {document.getElementById("pw_error").innerHTML ="";

         var x = document.forms["form1"]["results_here1"].value;
        if (x == null || x == "") { return true; }
        else { document.getElementById("results_here").innerHTML ="Record NOT Save. Duplicate Record Found!";document.getElementById("username").focus();return false; }
      }

    }

    function create_fullname()
    {
        form1.fullname.value=form1.title.value + ' ' + form1.firstname.value + ' ' + form1.middlename.value + ' ' + form1.lastname.value + ' ' + form1.extname.value;
    }

    function calculateAge() { // birthday is a date

  var dateString=form1.date_of_birth.value;
  var now = new Date();
  var today = new Date(now.getYear(),now.getMonth(),now.getDate());

  var yearNow = now.getYear();
  var monthNow = now.getMonth();
  var dateNow = now.getDate();

  var dob = new Date(dateString.substring(0,4),
                     dateString.substring(5,7),                   
                     dateString.substring(8,10)                  
                     );

  var yearDob = dob.getYear();
  var monthDob = dob.getMonth();
  var dateDob = dob.getDate();
  var age = {};
  var ageString = "";
  var yearString = "";
  var monthString = "";
  var dayString = "";


  yearAge = yearNow - yearDob;

  if (monthNow >= monthDob)
    var monthAge = monthNow - monthDob;
  else {
    yearAge--;
    var monthAge = 12 + monthNow -monthDob;
  }

  if (dateNow >= dateDob)
    var dateAge = dateNow - dateDob;
  else {
    monthAge--;
    var dateAge = 31 + dateNow - dateDob;

    if (monthAge < 0) {
      monthAge = 11;
      yearAge--;
    }
  }

  age = {
      years: yearAge,
      months: monthAge,
      days: dateAge
      };

  if ( age.years > 1 ) yearString = " years";
  else yearString = " year";
  if ( age.months> 1 ) monthString = " months";
  else monthString = " month";
  if ( age.days > 1 ) dayString = " days";
  else dayString = " day";


  if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
    ageString = age.years + yearString + ", " + age.months + monthString + ", and " + age.days + dayString + " old.";
  else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
    ageString = "Only " + age.days + dayString + " old!";
  else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
    ageString = age.years + yearString + " old. Happy Birthday!!";
  else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
    ageString = age.years + yearString + " and " + age.months + monthString + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
    ageString = age.months + monthString + " and " + age.days + dayString + " old.";
  else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
    ageString = age.years + yearString + " and " + age.days + dayString + " old.";
  else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
    ageString = age.months + monthString + " old.";
  else ageString = "Oops! Could not calculate age!";

  //form1.age.value= ageString;
  form1.age.value=  age.years ;

    }

function create_temp_pass(){

  form1.password.value=form1.username.value;
  form1.ReType.value=form1.username.value;
}

</script>
<?php require_once('../template/phplink.php'); ?>

<body>
  <?php require_once('../template/header.php'); ?>

  <div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<form  method="POST" name="form1" id="form1" onsubmit="return validateForm();" enctype="multipart/form-data">

  <div class="form-horizontal">
     
            <div class="form-group" align="center">

            <img id="image_preview1" src="../images/user/<?php echo $row_rs['id']; ?>.jpg" class="img-fluid" width="400px" height="400px"/>
            <div class="mb-4">
                
                <label for="image-upload1" class="form-label">Upload Photo  (size : 400x400 | Type: .jpg)</label>
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
          <div class="form-floating">
            <input readonly type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder=" " value="<?php echo htmlentities($row_rs['date_of_birth']); ?>" onblur="calculateAge();" onclick="calculateAge();">
            <label >Date of Birth*</label>
          </div>
        </div>

        <div class="form-group col-md-2 col-sm-12">
          <div class="form-floating">
            <input readonly type="text" class="form-control" name="age" id="age" placeholder=" " value="<?php echo htmlentities($row_rs['age']); ?>">
            <label >Age</label>
          </div>
        </div>

        </div>
        <br>
        <div class="row">

          <div class="form-group col-md-6 col-sm-12">
            <div class="form-floating">
              <input readonly type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder=" " value="<?php echo htmlentities($row_rs['place_of_birth']); ?>">
              <label >Place of Birth*</label>
            </div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
              <input readonly type="text" class="form-control" name="nationality" id="nationality" placeholder=" " value="<?php echo htmlentities($row_rs['nationality']); ?>">
              <label >Nationality*</label>
            </div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="text" class="form-control col-form-label"  name="gender" id="gender" placeholder=" " value="<?php echo htmlentities($row_rs['gender']); ?>">
                <label>Gender*</label>
              </div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
              <input readonly type="text" class="form-control" name="contact_no" id="contact_no" placeholder=" " value="<?php echo htmlentities($row_rs['contact_no']); ?>">
              <label >Contact No*</label>
            </div>
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
                  <input required name="password" type="password" class="form-control" id="password" value="<?php echo $phu->decryptMessage(htmlentities($row_rs['password'])); ?>"  placeholder=" ">
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
            <div class="form-floating">
              <input readonly type="text" class="form-control" name="group" id="group" placeholder=" " value="<?php echo htmlentities($row_rs['group']); ?>">
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
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="fil" id="fil" placeholder=" " value="<?php echo htmlentities($row_rs['fil']); ?>"> 
                <label >Flight Instructor License</label>
              </div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="cpl" id="cpl" placeholder=" " value="<?php echo htmlentities($row_rs['cpl']); ?>">
                <label >Commercial Pilot License</label>
              </div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="ntc" id="ntc" placeholder=" " value="<?php echo htmlentities($row_rs['ntc']); ?>">
                <label >NTC </label>
              </div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="medl" id="medl" placeholder=" " value="<?php echo htmlentities($row_rs['medl']); ?>">
                <label >Medical License</label>
              </div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="elp" id="elp" placeholder=" " value="<?php echo htmlentities($row_rs['elp']); ?>">
                <label >English Proficiency License</label>
              </div>
            </div>

            <div class="form-group col-md-2 col-sm-12">
              <div class="form-floating">
                <input readonly type="date" class="form-control" name="mecl" id="mecl" placeholder=" " value="<?php echo htmlentities($row_rs['mecl']); ?>">
                <label >Mechanic License</label>
              </div>
            </div>

        </div>
        <br>
        <div class="row">      
              <div class="form-group col-md-4 col-sm-12">
                <div class="form-floating"><input readonly type="number" min="0" class="form-control" name="required_hours" id="required_hours" placeholder=" " value="<?php echo htmlentities($row_rs['required_hours']); ?>"
                ><label >Required Hours</label></div>
              </div>

              <div class="form-group col-md-4 col-sm-12">
                <div class="form-floating"><input readonly type="number" min="0" class="form-control" name="required_hours" id="required_hours" placeholder=" " value="<?php echo htmlentities($row_rs['tgo_hours']); ?>"
                ><label >Total Touch and Go Hours</label></div>
              </div>

              <div class="form-group col-md-4 col-sm-12">
                <div class="form-floating"><input readonly type="number" min="0" class="form-control" name="required_hours" id="required_hours" placeholder=" " value="<?php echo htmlentities($row_rs['cc_hours']); ?>"
                ><label >Total Cross Country Hours</label></div>
              </div>

             
            </div>
          <br>
        <div class="form-group">
          <label class="col-md-2 control-label" align="right"></label>
          <div class="form-floating input-group">
            <button type="submit" class="btn btn-outline-primary"><span class="bi-save"></span> Save</button>
            <a href="../admin/index.php" class="btn btn-outline-danger "><span class="bi-x-octagon"></span> Cancel</a>
          </div>
        </div>

  <input type="hidden" name="POSTcheck" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_rs['id']; ?>">
  <input type="hidden" name="prev_id" id="prev_id" value="<?php if (isset($_POST['prev_id']))echo $_POST['prev_id']; else echo $row_rs['username'];?>">
  </fieldset>
  </div>
</form>


<!--------------------------------------------------------------------------------->
</div>
    <div class="card-footer"></div>
</div>

<?php require_once('../template/footer.php'); ?>
<script>
  function preview1() {
    image_preview1.src = URL.createObjectURL(event.target.files[0]);
  }
  function clearImage1() {
    document.getElementById('image-upload1').value = null;
    image_preview1.src = "";
  }
  </script>

<script>
function showhidepassword(){
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>
</body>
</html>
<?php ob_flush(); 
$db->close();
?>
