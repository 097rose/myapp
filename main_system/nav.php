<?php
session_start();

$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
//echo "Connected successfully";
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//prepare

$stmt = $conn -> prepare("select 店名 from shop where account=:account");
$stmt -> execute(array('account' => $_SESSION['Account']));


if($stmt->rowCount() == 0){
    $_SESSION['role'] = 'user';
}
else{
  $_SESSION['role'] = 'manager';
}
$link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
// $sql = "SELECT * FROM `food` WHERE 店名 = 'haha' ";
    
// $result = mysqli_query($link,$sql);
// $datas = array();
// if ($result) {
//   //echo 'www';
//     // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
//     if (mysqli_num_rows($result)>0) {
//         // 取得大於0代表有資料
//         // while迴圈會根據資料數量，決定跑的次數
//         // mysqli_fetch_assoc方法可取得一筆值
        
//         while ($row = mysqli_fetch_assoc($result)) {
//             // 每跑一次迴圈就抓一筆值，最後放進data陣列中
//             //echo 'www';
//             $datas[] = $row;
//         }
//     }
//     // 釋放資料庫查到的記憶體
//     mysqli_free_result($result);
// }



//$stmt = $conn ->prepare("select account from user where account=:account");
//$stmt -> execute(array('account' => $account));



?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.view_data').click(function(){
        var shop_name = $(this).attr("id");
        $.ajax({
          url:'menu.php',
          method:"post",
          data:{shop_name:shop_name},
          success: function(data){
            console.log(data);
            $('#menu_detail').html(data);
            //$('#'+shop_name)
            console.log($('#'+shop_name));
            //$('"#' + shop_name + '"').modal("show");
            $('#'+shop_name).modal("show");
          },
          error: function(){
            console.log("failed");
          }
        });
      });

    });
    
  </script>
  

  <title>Hello, world!</title>
  <script>
		function check_shop(shopName){
			if(shopName != ""){
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					var message;
					//console.log(this.readyState);
					//console.log(this.status);
					if(this.readyState == 4 && this.status == 200){
						document.getElementById("shopMsg").innerHTML = this.responseText;
					}
				};
				
				xhttp.open("POST", "check_shop.php", true);
				//window.location.replace("check_account.php");
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("shopName="+shopName);	
			}
			else{
				documnet.getElementById("shopMsg").innerHTML = "";
			}
		}
	</script>

</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">WebSiteName&nbsp;</a>
        <!-- <a class="navbar-brand " href="index.php" style="font-size:50%;">Log out</a> -->
      </div>

    </div>
  </nav>
  <div class="container">

  <ul class="nav nav-tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1">shop</a></li>
      <li><a href="logout.php">log out</a></li>


    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
            Accouont : <?php echo $_SESSION['username']?><br>
             role : <?php echo $_SESSION['role']?><br>   
             PhoneNumber: <?php echo $_SESSION['phone']?><br>  
             location: 
             <?php 
             echo $_SESSION['latitude'];
             echo ' , ';
             echo $_SESSION['longitude'];
             ?>

            
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!--  -->
            <form action="editlocation.php" method="post" class="fh5co-form animate-box" data-animate-effect="fadeIn">
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input type="text" class="form-control" name ='editlat' id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label " for="longitude">longitude</label>
                    <input type="text" class="form-control" name = 'editlon' id="longitude" placeholder="enter longitude">
                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Edit" class="btn btn-primary">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button> -->
                  </div>
                </div>
              </div>
            </div>
  </form>



            <!--  -->
            walletbalance: 100
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add value</button>
            <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add value</h4>
                  </div>
                  <div class="modal-body">
                    <input type="text" class="form-control" id="value" placeholder="enter add value">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" action="test.php" method="post">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="shopName" placeholder="Enter Shop name">
              </div>
              <label class="control-label col-sm-1" for="distance">distance</label>
              <div class="col-sm-5">


                <select class="form-control" name ="sel1" id="sel1">
                  <option>---</option>
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>

                </select>
              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">

                <input type="text" name="lowPrice" class="form-control">

              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">

                <input type="text" name="highPrice" class="form-control">

                </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" name="typeMeal" placeholder="Enter Meal">
                <!-- <datalist id="Meals" name="selectMeal">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist> -->
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" name="category" id="category" placeholder="Enter shop category">
                  <!-- <datalist id="categorys">
                    <option value="fast food">
               
                  </datalist>
                </div> -->
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
                
            </div>
          </form>
          <!-- <?PHP print_r($_SESSION['nameCategory']);?> -->
          <?php
                //echo $_SESSION['flag']; 
                $num = count($_SESSION['nameCategory']);
                //echo $num;
              ?>
        </div>
        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
               
                </tr>
              </thead>
              <tbody>
                
              <?php 
                  $i = 1;
                  foreach($_SESSION['nameCategory'] as $key => $val):
                    // $num = count($_SESSION['nameCategory']);
                    // if($num != 0){
                    //   unset($_SESSION['nameCategory']);
                    //   //$nameCategory = array();
                    // }
                ?>
                  <tr>
                    <th scope="row"><?php echo($i++)?></th>
                    <td><?php echo $key?></td>
                    <td><?php echo $val?></td>

                    <td><?php 
                          $close = 500;
                          $far = 1500;
                      
                          $stmt = $conn -> prepare("select ST_Distance_Sphere(point(:userLon, :userLat), point(longitude, latitude)) as dis from shop where 店名=:shopName");
                          $stmt -> execute(array('userLon' => $_SESSION['longitude'], 'userLat' => $_SESSION['latitude'], 'shopName' => $key));
                          $row = $stmt -> fetch();

                          if($row['dis'] < 500){
                            echo 'near';
                          }
                          else if($row['dis'] >= 500 && $row['dis'] <= 1500){
                            echo 'medium';
                          }
                          else{
                            echo 'far';
                          }
      
                      
                          ?> </td>
                          
                    <td>  <button type="button" class="btn btn-info  view_data " data-toggle="modal" data-target="#macdonald" id = "<?php echo $key; ?>" name = "open">Open menu</button></td>
                  </tr>
                  
                 
                  <?php 
                    if($num != 0){
                      //unset($_SESSION['nameCategory']);
                      $_SESSION['nameCategory'] = array();
                    }
                    
                    endforeach;?>

              </tbody>

            </table>
          
          

                <!-- Modal -->
                <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">menu</h4>
        </div>
        <div class="modal-body">
        </div>
         <!--  -->
  
         <div class="row">
          <div class="  col-xs-12">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <?php $a= $key?>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                 
                  <th scope="col">meal name</th>
               
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
  
                  <th scope="col">Order check</th>
                </tr>
              </thead>
              <tbody id="menu_detail">
              
              <?php 
               
              $sql1 = "SELECT * FROM `food` WHERE 店名 = 'haha' ";
    
              $result1 = mysqli_query($link,$sql1);
              $datas = array();
              if ($result1) {
                //echo 'www';
                  // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
                  if (mysqli_num_rows($result1)>0) {
                      // 取得大於0代表有資料
                      // while迴圈會根據資料數量，決定跑的次數
                      // mysqli_fetch_assoc方法可取得一筆值
                      
                      while ($row = mysqli_fetch_assoc($result1)) {
                          // 每跑一次迴圈就抓一筆值，最後放進data陣列中
                          //echo 'www';
                          $datas[] = $row;
                      }
                  }
                  // 釋放資料庫查到的記憶體
                  mysqli_free_result($result1);
              }
              
              
          foreach ($datas as $key1 => $row) : 
        ?>
                <tr>
                  <th scope="row"><?php echo($key1 +1 ); ?></th>
                  <td><?php $img=$row['Img'];
                            $logodata = $img;
              echo '<img src="data:'.$row['ImgType'].';base64,' . $logodata . '" width="70" height="70" / >'; ?></td>
                  
                  <td><?php echo($row['foodname'] ); ?></td>
                
                  <td><?php echo($row['price'] ); ?> </td>
                  <td><?php echo($row['quantity'] ); ?></td>
              
                  <td> <input type="checkbox" id="cbox1" value="$row['foodname'] "></td>
                </tr>
                <?php endforeach;
      mysqli_close($link); 
    ?>
   
     
              </tbody>
            </table>
          </div>

        </div>
        

         <!--  -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
        </div>
      </div>
      
    </div>
  </div>
          </div>

        </div>
      </div>
      <?php
          if($_SESSION['role'] == 'user')
          {
              require_once "shopregisterform.php";
          }
          else
          {
              require_once "shopready.php";
          }
      ?>

    </div>
  </div>


  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
    });
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>