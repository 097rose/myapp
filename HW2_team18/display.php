<script>  
  $(document).ready(function(){  
        $('.view_data').click(function(){  
            var foodname = $(this).attr("id");  
            $.ajax({  
                  url:"select.php",  
                  method:"post",  
                  data:{foodname:foodname},  
                  success:function(data){  
                      // $('#food').html(data); 
                      $('#Hamburger-1').modal("show");     
                  }  
            });  
        });  
  });  
 </script>
 <script>  
  $(document).ready(function(){  
        $('.view_data2').click(function(){  
            var foodname = $(this).attr("id");  
            $.ajax({  
                  url:"select.php",  
                  method:"post",  
                  data:{foodname:foodname},  
                  success:function(data){  
                       $('#food').html(data); 
                      //$('#Hamburger-1').modal("show");     
                  }  
            });  
        });  
  });  
 </script>
<?php

    //require_once('db.php');
    $account=$_SESSION['Account'];
    $sql1 = "SELECT shop.* FROM user LEFT JOIN shop ON user.account=shop.account  WHERE user.account = '$account' ";
    $result1 = mysqli_query($link,$sql1);
    if(mysqli_num_rows($result) > 0){
      while($row1 = $result1 -> fetch_assoc()){
        $_SESSION['店名'] = $row1['店名'];
        $_SESSION['餐點類型'] = $row1['餐點類型'];
      };
  }
    $ID = $_SESSION['店名'];
    
    
    $sql2 = "SELECT * FROM `food` WHERE 店名 = '$ID' ";
    
    $result2 = mysqli_query($link,$sql2);
    $datas = array();
    
    if (mysqli_num_rows($result2)>0) {
      // 取得大於0代表有資料
      // while迴圈會根據資料數量，決定跑的次數
      // mysqli_fetch_assoc方法可取得一筆值
     
      while ($row = mysqli_fetch_assoc($result2)) {
          // 每跑一次迴圈就抓一筆值，最後放進data陣列中
          
          $datas[] = $row;
      }
  }
    // if ($result) {
    //   //echo 'www';
    //     // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
    //     if (mysqli_num_rows($result)>0) {
    //         // 取得大於0代表有資料
    //         // while迴圈會根據資料數量，決定跑的次數
    //         // mysqli_fetch_assoc方法可取得一筆值
            
    //         while ($row = mysqli_fetch_assoc($result)) {
    //             // 每跑一次迴圈就抓一筆值，最後放進data陣列中
    //             echo 'www';
    //             $datas[] = $row;
    //         }
    //     }
    //     // 釋放資料庫查到的記憶體
    //     mysqli_free_result($result);
    // }
   
?>
<!doctype html>


<html>
    <div class="row">
    <div class="  col-xs-8">
      <table class="table" style=" margin-top: 15px;">
        <thead>
         <tr>
           <th scope="col">#</th>
            <th scope="col">Picture</th>
          <th scope="col">meal name</th>
        
          <th scope="col">price</th>
          <th scope="col">Quantity</th>
          <th scope="col">Edit</th>
         <th scope="col">Delete</th>
         </tr>
      </thead>
       
       <tbody> 
       
       <?php 
          foreach ($datas as $key => $row) : 
        ?>
    
      <tr>    
        <th scope="row"><?php echo($key +1 ); ?></th>
                  <td><?php $img=$row['Img'];
                            $logodata = $img;
              echo '<img src="data:'.$row['ImgType'].';base64,' . $logodata . '" width="200" height="200" / >'; ?>
                </td>
                <!-- <?php echo $row['foodname']; ?> -->
                  <td><?php echo($row['foodname'] ); ?></td>
            
                  <td><?php echo($row['price'] ); ?> </td>
                  <td><?php echo($row['quantity'] ); ?> </td>
                  <td><button type="button"  data-toggle="modal"  class="btn btn-info view_data"  id="<?php echo $row["foodname"]; ?>" >
                  Edit
                  </button></td>
                  
                  <!-- Modal -->

                      <div class="modal fade" id="Hamburger-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h3 class="modal-title" id="staticBackdropLabel">Food Edit</h3>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                        </div>
                          <form  action="edit.php" method="POST" enctype="multipart/form-data" id = "proc">
                            <div class="modal-body" id="food" >
                              <div class="row" >
                                  <div class="col-xs-6">
                                    <label for="ex71">price</label>
                                    <input class="form-control" id="ex71" type="text" name="price">
                                  </div>
                                  <div class="col-xs-6">
                                    <label for="ex41">quantity</label>
                                    <input class="form-control" id="ex41" type="text" name="quantity">
                                  </div>
                               </div>
                              
                            </div>
                            
                            <div class="modal-footer">
                              
                              <button type="submit" class="btn btn-danger"  >Edit</button>
                              
                            </div>
                          </form>
                          </div>
                        </div>

                      </div> 
                  <td>
                    <form action="delete.php"  method="post" enctype="multipart/form-data" >
                    <button type="submit" class="btn btn-danger view_data2" id = "<?php echo $row['foodname']; ?>" name = "delete" >Delete</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach;
      mysqli_close($link); 
    ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
</html>
   
