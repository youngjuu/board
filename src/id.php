<?   
  require('mconn.php'); 

  $id = $_POST["id"];  
  $pw = $_POST["pw"];

  $enc = $id.$pw;

  $sql="insert into id (id, password) 
        values ('$id', SHA('$enc'))";
        // DBMS_CRYPTO.Hash(to_clob(to_char('$id'||'$pw')),2)
        // md5($id||$pw)
  $result = mysqli_query($conn, $sql);

  mysqli_close($conn);
  
 if(!$result){
     echo(" <script>
             window.alert('계정 등록 장애입니다. $id, $pw');
             history.go(-1);
            </script>
          ");
      exit;
 }
 else
 echo(" <script>
          window.alert('회원가입을 축하합니다!');
        </script>
        <meta http-equiv='Refresh' content = '0; URL=view.php'>
      ");
?>