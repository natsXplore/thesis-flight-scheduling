<?php ob_start(); ?>
<?php require_once('../connections/pdoconnect.php'); ?>

<?php
    $phu=new php_util();
    $db=new DatabaseConnect();

    $flight_sched_id=$_POST['sched_id'];
    $flight_note=$_POST['note'];
   
    $SQLcrud = "UPDATE flight_sched SET note=? WHERE flight_sched_id=?";
    $db->query($SQLcrud);
    $db->bind(1,$flight_note );
    $db->bind(2,  $flight_sched_id);
    $db->execute();
    
    $db->close();

    $output = array(  
        'return_list' => json_encode("OK")
    
    );
                 
    echo json_encode($output);

    
    
?>

