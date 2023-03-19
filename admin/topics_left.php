
<?php
//topics_left.php
include('../class/handler_admin.php');

$object = new Handler;

if(isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
    //Execute the query
    $object->query = "SELECT promotor_liczba_tematow FROM promotor WHERE promotor_id = :promotor_id";
    $object->execute(array(':promotor_id' => $_SESSION['admin_id']));
    $result1 = $object->fetch();

    $object->query = "SELECT COUNT(*) as count FROM temat WHERE promotor_id = :promotor_id";
    $object->execute(array(':promotor_id' => $_SESSION['admin_id']));
    $result2 = $object->fetch();
    if($result1 && $result2){
    $result = array(
        'promotor_liczba_tematow' => $result1['promotor_liczba_tematow'],
        'dodane_tematy' => $result2['count']
    );
    echo json_encode($result);
    }else{
    echo "error";
    }
}else{
echo "error";
}

?>


