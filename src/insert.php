<?
    session_start();
    require('mconn.php');
    require('login.php');
    
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    
    if(empty($subject)){
        echo(" <script>
                window.alert('제목이 없습니다. 제목을 입력해주세요.');
                history.go(-1);
               </script>
           ");
       exit;
    }
    
    if(empty($content)){
        echo(" <script>
                window.alert('내용이 없습니다. 내용을 입력해주세요.');
                history.go(-1);
               </script>
            ");
      exit;
    }
    
    // board 테이블에 데이터 입력
    $sql = "INSERT INTO board (subject, hdate, hit, id) 
            VALUES ('$subject', NOW(), 0, '$id')";          
    $result1 = mysqli_query($conn, $sql);
    
    $sql = "SELECT MAX(bno) FROM board";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $bno_currval = $row[0]['MAX(bno)'];

    // content 테이블에 데이터 입력
    $sql = "INSERT INTO content (bno, content) 
            VALUES ($bno_currval, '$content')";
    $result2 = mysqli_query($conn, $sql);
    
    
    if((!$result1) || (!$result2)){
    echo("
         <script>
          window.alert('정상적으로 입력되지 않았습니다.');
          history.go(-1);
         </script>
        ");
    exit;
  }

  echo(" <meta http-equiv='Refresh' content = '0; URL=view.php'>");
?> 
