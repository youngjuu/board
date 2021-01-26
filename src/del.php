<?
  session_start();
  require('mconn.php');
  require('login.php');

  $bno = $_GET["bno"];

  // 본인 게시물만 삭제 가능
  $sql = "delete from board where bno='$bno' and id='$id'";
  $result = mysqli_query($conn, $sql);

  if(!$result){
      echo("
            <script>
                window.alert('삭제 실패');
                history.go(-1);
            </script>
           ") ;
      exit;
  }

  echo(" <meta http-equiv='Refresh' content = '0; URL=view.php'>"); 
?>