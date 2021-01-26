<?
    $id = $_SESSION["id"];
    $pw = $_SESSION["pw"];
    
    if(empty($id)){
        $login = 0;
    }
    else {
        $enc = $id.$pw;
        $sql="select id, password from id where id='$id' and password=SHA('$enc')";
         // echo("sql = $sql<br>");
         
         $result = mysqli_query($conn, $sql);
         
         $row_num = mysqli_num_rows($result);
         if($row_num==1){
             $_SESSION["id"] = $id;
             $_SESSION["pw"] = $pw;
             $login = $row_num;            // $row_num이 1 이면 로그인 성공, $login은 1.
         }
         else{
             $login = 0;
             $_SESSION["id"] = '';         // 안전을 위해 세션 인증 변수를 초기화 한다.
             $_SESSION["pw"] = '';         // 꼭 해야 하는것은 아님 !!
         }
    }
    
    if($login==0){
        echo(" <script>
                window.alert('로그인 해주세요.');
                history.go(-1);
               </script>
           ");
      exit;
    }
//   echo("login=$login &nbsp; id=$id  &nbsp; pw=$pw <br>");
?>