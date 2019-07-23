<?php 
 $conn = new mysqli("localhost","root","","crud_vue");
 if($conn->connect_error){
     die("Connection Failed!".$conn->connection_error);
 }
 

 $result = array('error'=>false);
 $action = '';

 if(isset($_GET['action'])){
     $action = $_GET['action'];
 }

 if($action == 'read'){
     $sql = $conn->query("SELECT * FROM visitors");
     $visitors = array();
     while($row = $sql->fetch_assoc()){
         array_push($visitors, $row);
     }
     $result['visitors'] = $visitors;
 }

 if($action == 'create'){
     $name = $_POST['name'];
     $number = $_POST['number'];
     $carNumber = $_POST['carNumber'];
     $hostName = $_POST['hostName'];

    $sql = $conn->query("INSERT INTO visitors(name,number,carNumber,hostName) VALUES('$name','$number','$carNumber','$hostName')");

  if($sql){
      $result['message'] = "სტუმარი წარმატებით დაემატა";
  } else {
      $result['error'] = true;
      $result['message'] = "სტუმრის დამატება ვერ მოხერხდა";
  }
}


// FOR EDITING (WILL BE USED FOR ADMIN)

/* if($action == 'update'){
    $name = $_POST['name'];
    $number = $_POST['number'];
    $carNumber = $_POST['carNumber'];
    $hostName = $_POST['hostName'];

   $sql = $conn->query("UPDATE visitors SET name='$name', number='$number', carNumber='$carNumber', hostName='$hostName'");

 if($sql){
     $result['message'] = "სტუმრის მონაცემები წარმატებით განახლდა";
 } else {
     $result['error'] = true;
     $result['message'] = "სტუმრის მონაცემების შესწორება ვერ მოხერხდა";
 }
}
*/
// FOR DELETING (WILL BE USED FOR ADMIN)

 if($action == 'delete'){
    $name = $_POST['name'];

   $sql = $conn->query("DELETE FROM visitors WHERE name='$name'");

 if($sql){
     $result['message'] = "სტუმრის მონაცემები წაიშალა";
 } else {
     $result['error'] = true;
     $result['message'] = "სტუმრის მონაცემების წაშლა ვერ მოხერხდა";
 }
}


 $conn->close();
 echo json_encode($result);
?>