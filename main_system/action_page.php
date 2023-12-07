<?php

session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

try {
    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated']!=true){
        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
                <script>
                alert("Please sign in first !!");
                window.location.replace("index.php");
                </script>
            </body>
            </html>
        EOT;
        exit();
    }

    // set the PDO error mode to exception
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    //echo "Connected successfully";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $_SESSION['shopExist'] = false;
    $_SESSION['nameList'] = array();
    $_SESSION['categoryList'] = array();

    $close = 500;
    $far = 1500;
    
    if(!empty($_POST['shopName'])){
        $nameList = array();
        $categoryList = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型 from shop where upper(店名) like upper(:shopName)");
        $stmt -> execute(array('shopName' => '%'.$_POST['shopName'].'%'));

 
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                array_push($nameList, $row['店名']);
                array_push($categoryList, $row['餐點類型']);
            }
            $_SESSION['shopExist'] = true;
            $_SESSION['nameList'] = $nameList;
            $_SESSION['categoryList'] = $categoryList;
        }

        header('location: nav.php');
        exit();
    }
    if(!empty($_POST['sel1'])){
        $nameList = array();
        $categoryList = array();
        $dis = $_POST['sel1'];

        if($dis == 'near'){//近
            $nameList = array();
            $categoryList = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) < :close");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'close' => $close));
            
            if($stmt -> rowCount() != 0){
                while($row = $stmt -> fetch()){
                    array_push($nameList, $row['店名']);
                    array_push($categoryList, $row['餐點類型']);
                }
                if($_SESSION['shopExist']){
                    $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                    $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
                }
                else{
                    $_SESSION['shopExist'] = true;
                    $_SESSION['nameList'] = $nameList;
                    $_SESSION['categoryList'] = $categoryList;
                }
            }

            // $msg = $_SESSION['nameList'][0];
            // echo <<< EOT
            // <!DOCTYPE html>
            // <html>
            // <body>
            //     <script>
            //     alert("$msg");
            //     window.location.replace("index.php");
            //     </script>
            // </body>
            // </html>
            // EOT;
            // exit();
        }
        else if($dis == 'medium'){//中
            $msg = '2';
            //echo 'no';
            $nameList = array();
            $categoryList = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) between :close and :far");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'close' => $close, 'far' => $far));
            
            if($stmt -> rowCount() != 0){
                //echo 'erf';
                while($row = $stmt -> fetch()){
                    array_push($nameList, $row['店名']);
                    array_push($categoryList, $row['餐點類型']);
                }
                if($_SESSION['shopExist']){
                    $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                    $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
                }
                else{
                    $_SESSION['shopExist'] = true;
                    $_SESSION['nameList'] = $nameList;
                    $_SESSION['categoryList'] = $categoryList;
                }
            }
        }
        else{//遠
            $msg = '3';
            $nameList = array();
            $categoryList = array();
            $stmt = $conn -> prepare("select 店名, 餐點類型
                                        from shop
                                        where ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) > :far");
            $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'far' => $far));
            
            if($stmt -> rowCount() != 0){
                
                while($row = $stmt -> fetch()){
                    array_push($nameList, $row['店名']);
                    array_push($categoryList, $row['餐點類型']);
                }
                if($_SESSION['shopExist']){
                    $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                    $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
                }
                else{
                    $_SESSION['shopExist'] = true;
                    $_SESSION['nameList'] = $nameList;
                    $_SESSION['categoryList'] = $categoryList;
                }
            }
        }

        header('location: nav.php');
        exit();
    }
    if(!empty($_POST['category'])){
        $nameList = array();
        $categoryList = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型 from shop where upper(餐點類型) like upper(:category)");
        $stmt -> execute(array('category' => '%'.$_POST['category'].'%'));


        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                array_push($nameList, $row['店名']);
                array_push($categoryList, $row['餐點類型']);
            }
            if($_SESSION['shopExist']){
                $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
            }
            else{
                $_SESSION['shopExist'] = true;
                $_SESSION['nameList'] = $nameList;
                $_SESSION['categoryList'] = $categoryList;
            }
        }

        header('location: nav.php');
        exit();
    }
    if(!empty($_POST['lowPrice']) || !empty($_POST['highPrice'])){
        $nameList = array();
        $categoryList = array();
        $low = 0;
        $high = 9E18;

        if(!empty($_POST['lowPrice'])){
            $low = $_POST['lowPrice'];
        }
        if(!empty($_POST['highPrice'])){
            $high = $_POST['highPrice'];
        }
        echo $msg = $low." ".$high;
        $stmt = $conn -> prepare("select 店名, 餐點類型
                                            from shop
                                            where 店名 in (select 店名
                                                                from food
                                                                where price between :low and :high)");
        $stmt -> execute(array('low' => $low, 'high' => $high));

        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                array_push($nameList, $row['店名']);
                array_push($categoryList, $row['餐點類型']);
            }
            if($_SESSION['shopExist']){
                $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
            }
            else{
                $_SESSION['shopExist'] = true;
                $_SESSION['nameList'] = $nameList;
                $_SESSION['categoryList'] = $categoryList;
            }
        }
        // echo $stmt -> rowCount();

        // echo <<< EOT
        //     <!DOCTYPE html>
        //     <html>
        //     <body>
        //         <script>
        //         alert("$msg");
        //         window.location.replace("index.php");
        //         </script>
        //     </body>
        //     </html>
        // EOT;
        // exit();

        header('location: nav.php');
        exit();
    }
    if(!empty($_POST['typeMeal'])){
        $nameList = array();
        $categoryList = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型
                                    from shop
                                    where 店名 in (select 店名
                                                    from food
                                                    where upper(foodname) like upper(:meal))");
        $stmt -> execute(array('meal' => '%'.$_POST['typeMeal'].'%'));

        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                array_push($nameList, $row['店名']);
                array_push($categoryList, $row['餐點類型']);
            }
            if($_SESSION['shopExist']){
                $_SESSION['nameList'] = array_intersect($_SESSION['nameList'], $nameList);
                $_SESSION['categoryList'] = array_intersect($_SESSION['categoryList'], $categoryList);
            }
            else{
                $_SESSION['shopExist'] = true;
                $_SESSION['nameList'] = $nameList;
                $_SESSION['categoryList'] = $categoryList;
            }
        }

        header('location: nav.php');
        exit();
    }
    if(empty($_POST['shopName']) && empty($_SESSION['sel1']) && empty($_SESSION['lowPrice']) && empty($_SESSION['highPrice']) && empty($_SESSION['typeMeal']) && empty($_SESSION['typeCategory']) && empty($_SESSION['selectCategory'])){
        //throw new Exception('Please input at least one requirement !!');
        $nameList = array();
        $categoryList = array();
        $stmt = $conn -> prepare("select 店名, 餐點類型 from shop");
        //$stmt -> execute(array('account' => $_SESSION['Account']));
        
        if($stmt -> rowCount() != 0){
            while($row = $stmt -> fetch()){
                array_push($nameList, $row['店名']);
                array_push($categoryList, $row['餐點類型']);
            }
            $_SESSION['shopExist'] = true;
            $_SESSION['nameList'] = $nameList;
            $_SESSION['categoryList'] = $categoryList;
        }
        // //echo $_SESSION['nameList'];

        // foreach($_SESSION['nameList'] as $val){
        //     echo $val;
        // }
        // echo "<br>";
        // echo $_SESSION['shopExist'];
        // //echo $_SESSION['categoryList'];

        header('location: nav.php');
        exit();
    }   
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$conn = null;

?>