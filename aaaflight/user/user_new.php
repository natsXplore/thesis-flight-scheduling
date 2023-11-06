<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$totalRows_rscheck =0;
$msg="";

if ((isset($_POST["POSTcheck"])) && ($_POST["POSTcheck"] == "form1")) {
		
  $query_rscheck = "SELECT * FROM user WHERE firstname LIKE ? AND lastname LIKE ?" ;
  $db->query($query_rscheck);
  $db->bind(1,htmlentities($_POST['firstname']));
  $db->bind(2,htmlentities($_POST['lastname']));
  $rscheck = $db->rowset();
  $totalRows_rscheck = $db->rowcount();

  if ($totalRows_rscheck==0){
    $SQLcrud = "INSERT INTO `user` (`firstname`, `middlename`, `lastname`, `extname`, `date_of_birth`, `age`, `place_of_birth`, `nationality`, `gender`, `contact_no`, `address`, `blood_type`, `contact_person`, `contact_person_no`, `designation`, `username`, `fil`, `cpl`, `ntc`, `medl`, `elp`, `mecl`, `password`, `group`, required_hours) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
      $db->bind(25,htmlentities($_POST['required_hours']));
      $db->execute();
  //mhash(MHASH_MD5, $data, "MySecret"); 

      $allowed = array('jpg');
      $filenames="../images/user/". $db->lastinsertid().".jpg";

      if(isset($_FILES['image-upload1']) && $_FILES['image-upload1']['error'] == 0){
        $extension = pathinfo($_FILES['image-upload1']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($_FILES['image-upload1']['tmp_name'], $filenames)){   }
      }

    //$insertGoTo = "user_list.php";
    //header(sprintf("Location: %s", $insertGoTo));
  }

  else{
    $msg="Record NOT Save. Duplicate Name Found!";
  }

	
}

$query_rsdesignation = "SELECT * FROM designation ORDER BY `designation` ASC";
$db->query($query_rsdesignation);
$rsdesignation = $db->rowset();
$totalRows_rsdesignation = $db->rowcount();


$query_rsgroup = "SELECT * FROM user_group ORDER BY `group` ASC";
$db->query($query_rsgroup);
$rsgroup = $db->rowset();
$totalRows_rsgroup = $db->rowcount();

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

        if (spw != srepw)
        {document.getElementById("pw_error").innerHTML ="Password did NOT match!";return false;}
        else {document.getElementById("pw_error").innerHTML ="";}
        
        var x = document.forms["form1"]["results_here1"].value;
        if (x == null || x == "") { return true; }
        else { document.getElementById("results_here").innerHTML ="Record NOT Save. Duplicate Record Found!";document.getElementById("username").focus();return false; }
    }

    function create_fullname()
    {
        form1.fullname.value=form1.title.value + ' ' +form1.firstname.value + ' ' + form1.middlename.value + ' ' + form1.lastname.value + ' ' + form1.extname.value;
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
  form1.age.value= age.years;

    }

function set_default_required_hours(){
  let rh = document.getElementById('group');
  if (rh.value=="Student"){
    document.getElementById('required_hours').value="150";
  }else{
    document.getElementById('required_hours').value="0";
  }
}
</script>

<?php require_once('../template/phplink.php'); ?>


<body>
<?php require_once('../template/header.php'); ?>
<div class="card">
        <div class="card-header"><h5 class="card-title"><strong><?php echo htmlentities($_SESSION['title']); ?></strong></h5></div>
            <div class="card-body">
<!--------------------------------------------------------------------------------->
<?php if (strlen($msg)>0) { ?>
  <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert"> <?php echo $msg; ?> <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button></div>
<?php } ?>

<form method="POST" name="form1" id="form1" onsubmit="return validateForm();" enctype="multipart/form-data">
<div class="form-horizontal">
        <fieldset>
         
        
        <div class="form-group" align="center">
         <img id="image_preview1" src="" class="img-fluid" width="400px" height="400px"/>
            <div class="mb-4">
                <label for="image-upload1" class="form-label">Upload Photo  (size : 400x400 | Type: .jpg)</label>
                <input class="form-control" type="file" id="image-upload1" name="image-upload1" onchange="preview1()" accept="image/jpg">
                
            </div> 
       </div>

        <br>
        <div class="row">

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input required type="text" class="form-control" name="firstname" id="firstname" placeholder=" " value=""><label >First Name*</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input type="text" class="form-control" name="middlename" id="middlename" placeholder=" " value=""><label >Middle Name</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input required type="text" class="form-control" name="lastname" id="lastname" placeholder=" " value=""><label >Last Name*</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input type="text" class="form-control" name="extname" id="extname" placeholder=" " value=""><label >Ext. Name</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input required type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder=" " value="" onblur="calculateAge();" onclick="calculateAge();"><label >Date of Birth*</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input readonly type="text" class="form-control" name="age" id="age" placeholder=" " value=""><label >Age</label></div>
          </div>

        </div>
          <br>

        <div class="row">
          
          <div class="form-group col-md-6 col-sm-12">
            <div class="form-floating"><input required type="text" class="form-control" name="place_of_birth" id="place_of_birth" placeholder=" " value=""><label >Place of Birth*</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input required type="text" class="form-control" name="nationality" id="nationality" placeholder=" " value="Filipino"><label >Nationality*</label></div>
          </div>

          <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating">
                    <select name="gender" id="gender" class="form-select" placeholder="">
                      
                          <option value="male">male</option>
                          <option value="female">female</option>
                          <option value="LGBTQ">LGBTQ</option>
                    </select>
                    <label>Gender*</label>
                </div>
   
          </div>

          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating"><input type="text" class="form-control" name="contact_no" id="contact_no" placeholder=" " value=""><label >Contact No*</label></div>
          </div>

        </div>
        <br>
          <div class="form-group">
            <div class="form-floating ">
              <input required type="text" class="form-control col-form-label"  name="address" id="address" placeholder=" ">
              <label >Address</label>
            </div>
          </div>

          <br>
          <div class="row">
          <div class="form-group col-md-2 col-sm-12">
            <div class="form-floating">
              <select required name="designation" id="designation" class="form-select" placeholder=" ">
                <?php
                  foreach($rsdesignation as $row_rsdesignation) {  
                  ?>
                    <option value="<?php echo $row_rsdesignation['designation']?>"><?php echo htmlentities($row_rsdesignation['designation']);?></option>
                  <?php
                    }
                  ?>
                </select>
                <label for="designation">Designation*</label>
              </div>
          </div>
        
          <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating">
                    <select name="blood_type" id="blood_type" class="form-select" placeholder="">
                      <option value=""></option>
                      <option value="A+">A+</option>
                      <option value="B+">B+</option>
                      <option value="O+">O+</option>
                      <option value="AB+">AB+</option>
                      <option value="A-">A-</option>
                      <option value="B-">B-</option>
                      <option value="O-">O-</option>
                      <option value="AB-">AB-</option>
                    </select>
                    <label>Blood Type</label>
                </div>
          </div>

          <div class="form-group col-md-5 col-sm-12">
            <div class="form-floating ">
              <input required type="text" class="form-control col-form-label"  name="contact_person" id="contact_person" placeholder=" ">
              <label >Contact Person*</label>
            </div>
          </div>

          <div class="form-group col-md-3 col-sm-12">
            <div class="form-floating ">
              <input required type="text" class="form-control col-form-label"  name="contact_person_no" id="contact_person_no" placeholder=" ">
              <label >Contact No*</label>
            </div>
          </div>
                
          </div>
            <br>
            <div class="row">

              <div class="form-group col-md-4 col-sm-12">
              <span id="results_here" class="alert-danger"></span> 
                <div class="form-floating">
                  <input type="hidden" id="results_here1">
                  <input required type="email" class="form-control col-form-label"  name="username" id="username" placeholder=" "
                    OnKeyUp="showAjax('user_duplicate.php','txtString',this.value, 'results_here');" Onblur="showAjax('user_duplicate.php','txtString',this.value, 'results_here');">
                  
                <label for="username">Email/UserName*</label>
                </div>
              </div>

              <div class="form-group  col-md-2 col-sm-12">
                <div id="pw_error" id="pw_error" class="alert-danger"></div>
                <div class="form-floating ">
                  <input required type="password" class="form-control col-form-label"  name="password" id="password" placeholder=" "> 
                  <label>Password*</label>
                </div>
                <input class="form-check-input" type="checkbox" id="gridCheck1" onclick="showhidepassword()"> <label class="form-check-label" for="gridCheck1">Show Password</label>
              </div>
   
              <div class="form-group  col-md-2 col-sm-12">
                    <div class="form-floating ">
                        <input required type="password" class="form-control col-form-label"  name="ReType" id="ReType" placeholder=" ">
                        <label >Re-Type Password*</label>
                    </div>
              </div>

              <div class="form-group  col-md-2 col-sm-12">
                <div class="form-floating ">
                    <select name="group" id="group" class="form-select" placeholder=" " onchange="set_default_required_hours();">
                      <?php
                    foreach($rsgroup as $row_rsgroup) {  
                      ?>
                          <option value="<?php echo $row_rsgroup['group']?>"
                          <?php if (isset($_POST['group']) && !(strcmp($row_rsgroup['group'], $_POST['group']))){echo "selected=\"selected\"";}?>><?php echo htmlentities($row_rsgroup['group']);?></option>
                          <?php
                        }
                      ?>
                    </select>
                    <label >User Group*</label>
                </div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating">
                    <select name="status" id="status" class="form-select" placeholder="">
                      
                          <option value="active">active</option>
                          <option value="disabled">disabled</option>
                         
                    </select>
                    <label>Status*</label>
                </div>
              </div>

            </div>
            <br><br>
           
            <div class="row">      
              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="fil" id="fil" placeholder=" " value=""
                ><label >Flight Instructor License</label></div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="cpl" id="cpl" placeholder=" " value=""
                ><label >Commercial Pilot License</label></div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="ntc" id="ntc" placeholder=" " value=""
                ><label >NTC </label></div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="medl" id="medl" placeholder=" " value=""
                ><label >Medical License</label></div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="elp" id="elp" placeholder=" " value=""
                ><label >English Proficiency License</label></div>
              </div>

              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="date" class="form-control" name="mecl" id="mecl" placeholder=" " value=""
                ><label >Mechanic License</label></div>
              </div>

            </div>
            <br>
            <div class="row">      
              <div class="form-group col-md-2 col-sm-12">
                <div class="form-floating"><input  type="number" min="0" class="form-control" name="required_hours" id="required_hours" placeholder=" " value="0"
                ><label >Required Hours</label></div>
              </div>

             
            </div>
<br>
     <div class="form-group">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <button type="submit" class="btn btn-outline-primary"><span class="bi-save"></span> Save</button>
          <a href="user_list.php" class="btn btn-outline-danger hidelink"><span class="bi-x-octagon"></span> Cancel</a>
          </div>
        </div>

    </fieldset>  
  </div>

  <input type="hidden" name="POSTcheck" value="form1">
</form>
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
