<?php
    session_start(); 
    require_once('db.php');
    //$PID = $_POST['delete'];
    $PID = $_SESSION["foodname"];

    $SID = $_SESSION['店名'];
    echo $SID;

$sql = "DELETE FROM  `food` WHERE foodname = '$PID' AND 店名 = '$SID' ";

// 用mysqli_query方法執行(sql語法)將結果存在變數中
$result = mysqli_query($link,$sql);

// 如果有異動到資料庫數量(更新資料庫)
if (mysqli_affected_rows($link)>0) {
// 如果有一筆以上代表有更新
// mysqli_insert_id可以抓到第一筆的id
$new_id= mysqli_insert_id ($link);
    echo "刪除成功";
    header('location: nav.php#menu1');
}
elseif(mysqli_affected_rows($link)==0) {
    echo "無資料新增";
}
else {
    echo "{$sql} 語法執行失敗，錯誤訊息: " . mysqli_error($link);
}
mysqli_close($link); 
unset($_SESSION['foodname']);
unset($_SESSION['店名']);
 ?>