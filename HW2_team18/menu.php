<html>




<?php 
$dbservername='localhost';
$dbname='hw2';
$dbusername='root';
$dbpassword='000000';
$link = mysqli_connect($dbservername,$dbusername,$dbpassword,$dbname);
$a= $_POST['shop_name'];
$sql1 = "SELECT * FROM `food` WHERE 店名 = '$a' ";
    
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

</html>