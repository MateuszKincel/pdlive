
<?php
//topics_left.php
include('../class/handler_admin.php');

$object = new Handler;
$sum = 0;

if(isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
    //Execute the query
    $object->query = "SELECT SUM(promotor_liczba_tematow) as total FROM promotor";
    $object->execute();
    $result1 = $object->fetch();
    if($result1){
        $sum = $result1['total'];
        echo $sum;
    }else{
        echo "error";
    }
}else{
    echo "error";
}
?>


