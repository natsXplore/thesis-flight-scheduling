<?php require_once('../connections/pdoconnect.php'); 

$phu=new php_util();
$menu_id=$phu->get_menu_id(basename($_SERVER['PHP_SELF']));
$db=new DatabaseConnect();

$action = $_POST['action'];
$aid = $_POST['var_aid'];
$sid = str_replace("s","",$_POST['var_sid']);
$iid = $_POST['var_iid'];
$flight_sched_slug = $_POST['flight_sched_slug'];

$return_list="";
$total_seat=0;
$error="";

if ($action=="check_aircraft"){

    $total_aircraft=0;

    $query_rs = "SELECT * FROM `flight_sched_passenger` WHERE `flight_sched_slug`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_slug);
    $rscheck_aircraft=$db->rowsingle();
    $rscheck_aircraft_total=$db->rowcount();
    
    $SQLcrud = "UPDATE lup_aircraft SET flight_status=?,flight_sched_slug=?  WHERE flight_sched_slug=?";
    $db->query($SQLcrud);
    $db->bind(1,'');
    $db->bind(2,'');
    $db->bind(3,$flight_sched_slug);
    $db->execute();

    if ($rscheck_aircraft_total==0){
        $SQLcrud = "INSERT INTO flight_sched_passenger (lup_aircraft_id, flight_sched_slug, user_id) VALUES (?,?,?)";
        $db->query($SQLcrud);
        $db->bind(1,$aid);
        $db->bind(2,$flight_sched_slug);
        $db->bind(3,0);
        $db->execute();
    }else {
        

        $SQLcrud = "UPDATE flight_sched_passenger SET lup_aircraft_id=? WHERE flight_sched_slug=?";
        $db->query($SQLcrud);
        $db->bind(1,$aid);
        $db->bind(2,$flight_sched_slug);
        $db->execute();
    }

    $query_rs = "SELECT * FROM `lup_aircraft` WHERE `lup_aircraft_id`=? AND aircraft_status=?";
    $db->query($query_rs);
    $db->bind(1,$aid);
    $db->bind(2,'active');
    $rsaircraft=$db->rowsingle();
    $rsaircraft_total=$db->rowcount();
    $total_seat=$rsaircraft['seat'];
    $total_aircraft=$rsaircraft_total;

    $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_slug);
    $db->bind(2,'Student');
    $rscount_student=$db->rowset();
    $rscount_student_total=$db->rowcount();

    $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_slug);
    $db->bind(2,'Instructor');
    $rscount_instuctor=$db->rowset();
    $rscount_instructor_total=$db->rowcount();

    if($total_aircraft>0 && intval($rscount_instructor_total)>0 && intval($rscount_student_total)>0){
        
        if ((intval($rscount_instructor_total)+intval($rscount_student_total))>intval($total_seat)){
            $error="Too Many student/passenger selected. Aircraft capacity is only ".$total_seat." persons including the Instructor.";

        }      
    }elseif($total_aircraft>0 && intval($rscount_instructor_total)==0 && intval($rscount_student_total)>0){
        
        if (intval($rscount_student_total)>intval($total_seat)-1){
            $error="Too Many student/passenger selected. Aircraft capacity is only ".$total_seat." persons including the Instructor.";
        }      
    }elseif($rscount_student_total==0){
        $error=" ";
    }

}elseif ($action=="check_student"){
    //$error="check_student";
    $total_aircraft=0;
    $aid=0;

    $query_rs = "SELECT la.* FROM flight_sched_passenger fsp INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE fsp.`flight_sched_slug`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_slug);
    $rscheck_aircraft=$db->rowsingle();
    $rscheck_aircraft_total=$db->rowcount();

    if ($rscheck_aircraft_total>0){
        $aid=$rscheck_aircraft['lup_aircraft_id'];
        $total_seat=$rscheck_aircraft['seat'];
        $total_aircraft=$rscheck_aircraft_total;
    } 

        $query_rs = "SELECT * FROM flight_sched_passenger WHERE `flight_sched_slug`=? AND user_id=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,$sid);
        $rsstudent=$db->rowsingle();
        $rsstudent_total=$db->rowcount();
        

        if (intval($rsstudent_total)==0){
            $SQLcrud = "INSERT INTO flight_sched_passenger (`user_id`, lup_aircraft_id, flight_sched_slug ) VALUES (?,?,?)";
            $db->query($SQLcrud);
            $db->bind(1,$sid);
            $db->bind(2,$aid);
            $db->bind(3,$flight_sched_slug);
            $db->execute();

            $SQLcrud = "UPDATE user SET flight_status=? WHERE id=?";
            $db->query($SQLcrud);
            $db->bind(1,'Flight Schedule Active');
            $db->bind(2,$sid);
            $db->execute();

        }else{
            $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=?";
            $db->query($SQLcrud);
            $db->bind(1,$sid);
            $db->bind(2,$flight_sched_slug);
            $db->execute();

            $SQLcrud = "UPDATE user SET flight_status=? WHERE id=?";
            $db->query($SQLcrud);
            $db->bind(1,'');
            $db->bind(2,$sid);
            $db->execute();
        }

        

        $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=?";
        $db->query($SQLcrud);
        $db->bind(1,'0');
        $db->bind(2,$flight_sched_slug);
        $db->execute();

        $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,'Student');
        $rscount_student=$db->rowset();
        $rscount_student_total=$db->rowcount();
    

        $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,'Instructor');
        $rscount_instuctor=$db->rowset();
        $rscount_instructor_total=$db->rowcount();

      
        if($total_aircraft>0 && intval($rscount_instructor_total)>0 && intval($rscount_student_total)>0){
            
            if ((intval($rscount_instructor_total)+intval($rscount_student_total))>intval($total_seat)){
                $error="Too Many student/passenger selected. Aircraft capacity is only ".$total_seat." persons including the Instructor.";
                
                    $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=?";
                    $db->query($SQLcrud);
                    $db->bind(1,$sid);
                    $db->bind(2,$flight_sched_slug);
                    $db->execute();
                

            }      
        }elseif($total_aircraft>0 && intval($rscount_instructor_total)==0 && intval($rscount_student_total)>0){
            //$total_seat=$rsaircraft['seat'];
            if (intval($rscount_student_total)>intval($total_seat)-1){
                $error="Too Many student/passenger selected. Aircraft capacity is only ".$total_seat." persons including the Instructor.";

                
                    $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=?";
                    $db->query($SQLcrud);
                    $db->bind(1,$sid);
                    $db->bind(2,$flight_sched_slug);
                    $db->execute();
                
            }      
        }elseif($rscount_student_total==0){
            $error=" ";
        }

       

}elseif ($action=="check_instructor"){
    //$error="check_student";
    $total_aircraft=0;
    $aid=0;

    $query_rs = "SELECT la.* FROM flight_sched_passenger fsp INNER JOIN lup_aircraft la ON la.lup_aircraft_id=fsp.lup_aircraft_id WHERE fsp.`flight_sched_slug`=?";
    $db->query($query_rs);
    $db->bind(1,$flight_sched_slug);
    $rscheck_aircraft=$db->rowsingle();
    $rscheck_aircraft_total=$db->rowcount();
    if ($rscheck_aircraft_total>0){
        $aid=$rscheck_aircraft['lup_aircraft_id'];
        $total_aircraft=$rscheck_aircraft_total;
    }
        


        $SQLcrud = "DELETE FROM flight_sched_passenger WHERE user_id=? AND flight_sched_slug=?";
        $db->query($SQLcrud);
        $db->bind(1,'0');
        $db->bind(2,$flight_sched_slug);
        $db->execute();

        $query_rs = "SELECT * FROM flight_sched_passenger fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.`group`=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,'Instructor');
        $rsinstructor=$db->rowsingle();
        $rsinstructor_total=$db->rowcount();
        
        if ($rsinstructor_total>0){
        $SQLcrud = "UPDATE user SET flight_status=? WHERE id=?";
        $db->query($SQLcrud);
        $db->bind(1,'');
        $db->bind(2,$rsinstructor['user_id']);
        $db->execute();

        #delete existing Instructor
        $SQLcrud = "DELETE FROM flight_sched_passenger WHERE `user_id`=? AND flight_sched_slug=?";
        $db->query($SQLcrud);
        $db->bind(1,$rsinstructor['user_id']);
        $db->bind(2,$flight_sched_slug);
        $db->execute();
        }


        #then insert new instructor
        $SQLcrud = "INSERT INTO flight_sched_passenger (`user_id`, lup_aircraft_id, flight_sched_slug ) VALUES (?,?,?)";
        $db->query($SQLcrud);
        $db->bind(1,$iid);
        $db->bind(2,$aid);
        $db->bind(3,$flight_sched_slug);
        $db->execute();        

        $SQLcrud = "UPDATE user SET flight_status=? WHERE id=?";
        $db->query($SQLcrud);
        $db->bind(1,'Flight Schedule Active');
        $db->bind(2,$iid);
        $db->execute();
        

        $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,'Student');
        $rscount_student=$db->rowset();
        $rscount_student_total=$db->rowcount();
    
        $query_rs = "SELECT * FROM `flight_sched_passenger` fsp INNER JOIN user u ON u.id=fsp.user_id WHERE fsp.`flight_sched_slug`=? AND u.group=?";
        $db->query($query_rs);
        $db->bind(1,$flight_sched_slug);
        $db->bind(2,'Instructor');
        $rscount_instuctor=$db->rowset();
        $rscount_instructor_total=$db->rowcount();

      

        if($total_aircraft>0){
            $query_rs = "SELECT * FROM `lup_aircraft` WHERE `lup_aircraft_id`=? AND aircraft_status=?";
            $db->query($query_rs);
            $db->bind(1,$aid);
            $db->bind(2,'active');
            $rsaircraft=$db->rowsingle();
            
            $total_seat=$rsaircraft['seat'];
            if ((intval($rscount_instructor_total)+intval($rscount_student_total))>intval($total_seat)){
                $error="Too Many student/passenger selected. Aircraft capacity is only ".$total_seat." persons including the Instructor.";
            }
        }
}

$output = array(  
    'return_list' => $return_list,
    'error'      =>  $error,
    'total_seat' => $total_seat 

);
             
echo json_encode($output); 
?>