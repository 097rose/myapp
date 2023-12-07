<?php
session_start();


$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$errors = array();
$flag = false;

try{
  
    if(empty($_POST['editlat'])||empty($_POST['editlon'])){
        throw new Exception("請輸入經度和緯度");

    }
    if (preg_match("/^[0-9]+(.[0-9]*)?$/", $_POST['editlat']) == 0 || $_POST['editlat'] > 90 || $_POST['editlat'] < -90){
        throw new Exception("經度不正確");
      }
      if($_POST['editlat'] < -90 || $_POST['editlat'] > 90){
        throw new Exception("經度不正確");
      }
      if (preg_match("/^[0-9]+(.[0-9]*)?$/", $_POST['editlon']) == 0 || $_POST['editlon'] > 180 || $_POST['editlon'] < -180){
        throw new Exception("緯度不正確");
      }
      if($_POST['editlon'] < -180 || $_POST['editlon'] > 180){
        throw new Exception("緯度不正確");
      }
    else{
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // echo $_SESSION['Account'];
        // echo $_SESSION['latitude'];
    
        // echo '<br>';
        $account = $_SESSION['Account'];
        //$a=$_SESSION['phone'];
         $latitude = $_POST['editlat'];
         $longitude = $_POST['editlon'];
    //     $point = 'POINT(' . $latitude . ' ' . $longitude . ')';
    //     //$float=st_astext($point);
    //     //$aa=ST_GeomFromText('POINT(121.366961 31.190049)');
    //    // echo $point;
    //     $aa=(explode(' ',$point));
    //     //echo $aa[0];
    //     //echo'a';
    //     echo str_replace('POINT(','',$aa[0]);
    //     echo ' , ';
    //     echo str_replace(')','',$aa[1]);
    //     echo '<br>';
    
        //echo gettype(ST_GeomFromText($point));
    
        // $sql="UPDATE user SET location = 'ST_GeomFromText($point)' where account = '$account'";
        // $phone = 2222211111;
    
        // $stmt = $conn -> prepare("UPDATE user SET phone = :phone where account = :account");
        // $stmt->execute(array('account' => $account, 'phone' => $phone));
        // $stmt = $conn -> prepare("select phone from user where account=:account");
        // $stmt->execute(array('account' => $account));
    
        //$row = $stmt -> fetch();
        
        // $stmt = $conn -> prepare("select phone from user where account=:account");
        // $stmt -> execute(array('account' => $account));
        // if($stmt->rowCount() == 1){
        //     echo $_SESSION['phone'];
    
        // }
        $sql1="UPDATE user SET latitude = '$latitude' where account = '$account'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
    
        $sql2="UPDATE user SET longitude = '$longitude' where account = '$account'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
    
    
        $conn = new mysqli($dbservername,$dbusername,$dbpassword,$dbname);
        $sql = "select * from user where account = '$account'";
        $result = $conn->query($sql);
        
    
        if(mysqli_num_rows($result) > 0){
            while($row = $result -> fetch_assoc()){
                $_SESSION['latitude']=$row['latitude'];
                $_SESSION['longitude']=$row['longitude'];
        
            };
        }
        
        header('location: nav.php');
    }


}

catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
}
catch(Exception $e){
    $msg=$e->getmessage();
    //session_unset();
    //session_destroy();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
            alert("$msg");
            window.location.replace("nav.php");
            </script>
        </body>
        </html>
     EOT;


}

$conn=null;

?>