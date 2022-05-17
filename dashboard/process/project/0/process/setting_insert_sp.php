<?php include('../../../function.php');

// ---- commission percent for support code ----
$commision = ( 1000 * $bonus_class_2 ) / 100;

if (isset($_GET['action']) && $_GET['action'] == 'insert') {
    $number_of_insert = $_GET['number'];
    for ($i = 0; $i < $number_of_insert; $i++) { 
        insert_support_code($connect, 0, $com_number, $commision);
    }
    header("location:../../../../admin.php?page=special&action=liner");
}
?>