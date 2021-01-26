<?
session_start();
require('mconn.php');

//========================================================= 로그인 및 세션 =========================================================//
if(empty($_GET["logout"])){}
else{
    session_destroy();
    $_SESSION["id"] = '';
    $_SESSION["pw"] = '';
}

if(empty($_POST["fid"]) && empty($_POST["fpw"])){
    $id = $_SESSION["id"];        // Post 방식으로 fid, fpw가 전달 되지 않았을때                   
    $pw = $_SESSION["pw"];        // $id 와 $pw는 세션변수를 취한다. 세션값이 없으면 널값이다.
}
else{
    $id = $_POST["fid"];          // 로그인을 위해 id, pw를 입력한 경우  
    $pw = $_POST["fpw"];          // POST 방식으로 fid, fpw 가 $id와 $pw에 전달됨
}

if(empty($id)){
    $login = 0;
}
else {
    $enc = $id.$pw;
    $sql="select id, password from id where id='$id' and password=SHA('$enc')";
    // echo("sql = $sql<br>");
    $result = mysqli_query($conn, $sql);
    
    $row_num = mysqli_num_rows($result);
    if($row_num == 1){
        $_SESSION["id"] = $id;
        $_SESSION["pw"] = $pw;
        $login = $row_num;                 // $row_num이 1 이면 로긴 성공, $login은 1.
    }
    else{
      $login = 0;                        // $row_num이 1 이 아니면 부정.
      $_SESSION["id"] = '';              // 안전을 위해 세션 인증 변수를 초기화 한다.
      $_SESSION["pw"] = '';              // 꼭 해야 하는것은 아님 !!
    }
}
//echo("login=$login &nbsp; id=$id  &nbsp; pw=$pw <br> sql = $sql");

//========================================================= 검색 및 화면단위 출력 =========================================================//
$search = $_GET["search"];
if (empty($search)){
    $sql="select bno, id, subject, hdate, hit from board order by bno desc";
}
else{
    $sql="select bno, id, subject, hdate,hit from board
          where subject like '%{$search}%' order by bno desc";
}

$result = mysqli_query($conn, $sql);
$row_num = mysqli_num_rows($result);
$row = mysqli_fetch_all($result, MYSQLI_ASSOC);

$scale = 10;

$start = $_GET["start"];
if (empty($start)) {$start=0;}
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>게시판</title>
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">	

<!-- 인증 시작 계정:id, 패스워드:pw, 폼이름:login, 전달방식:post  -->
<!-- $login  1:로그인 됨, 이외값:로그인 이전                                 -->
<table width='700' align='center' cellpadding='0' cellspacing='0'>
	<form name='login' action='view.php' method='post'>
    <tr align='center' height='25'>
        <td width='40' height='25' bordercolor='white' bgcolor='white'>
		  <p align='right'>
<?		 
  if($login == 1){      // 로그인됨
    echo("
                <span style='font-size:9pt'>
                {$id}님 환영합니다. &nbsp;&nbsp;&nbsp;&nbsp;
                <a href='insert.html'>글쓰기</a>&nbsp;&nbsp;
                <a href='view.php?logout=$login'>로그아웃</a>
                </span>
         ");
  }
  else{                 // 로그인되지 않음
    echo("      
                <span style='font-size:9pt'>
                <a href='./id.html'>회원가입</a> &nbsp;&nbsp;&nbsp;&nbsp;
                id : <input type='text' name='fid' size='20' class='inputBox'>&nbsp;
                pw : <input type='password' name='fpw' size='20' class='inputBox'>&nbsp;
                <input type='submit' name='로그인' value='로그인' style='font-size:9pt;'>
                </span>
         "); 
  }
    
?>
		  </p>
		</td>
	</tr>
	</form>
</table>
<!-- 인증  종료  -->

<table width="700" align="center" cellpadding="0" cellspacing="0">
    <tr align="center" height='25'>
        <td width="40" height="25" bordercolor="white" bgcolor="#0033FF">
            <font color="white"><span style="font-size:9pt;">번호</span></font>
        </td>
        <td bgcolor="#0033FF" bordercolor="white">
            <font color="white"><span style="font-size:9pt;">제목</span></font>
        </td>
        <td width="20" bgcolor="#0033FF" bordercolor="white">
            <span style="font-size:9pt;">&nbsp;</span></p>
        </td>
        <td width="60" bgcolor="#0033FF" bordercolor="white">
            <font color="white"><span style="font-size:9pt;">글쓴이</span></font>
        </td>
        <td width="70" bgcolor="#0033FF" bordercolor="white">
            <font color="white"><span style="font-size:9pt;">날짜</span></font>
        </td>
        <td width="30" bgcolor="#0033FF" bordercolor="white">
            <font color="white"><span style="font-size:9pt;">조회</span></font>
        </td>
    </tr>
<?
  for($i=$start; $i<($start+$scale); $i++) 
     {
      if($i < $row_num)
        {
         echo("
    <tr align='center' height='25'>
        <td>
            <span style='font-size:9pt;'> {$row[$i]['bno']} </span>
        </td>
        <td>
            <p align='left'>
              &nbsp;&nbsp;&nbsp;
              <a href='./content.php?start=$start&search=$search&bno={$row[$i]['bno']}'>
              <span style='font-size:9pt;'>{$row[$i]['subject']}</span>
              </a>
            </p>
        </td>
        <td>
            <span style='font-size:9pt;'>&nbsp;</span>
        </td>
        <td>
            <span style='font-size:9pt;'>{$row[$i]['id']}</span>
        </td>
        <td>
            <span style='font-size:9pt;'>{$row[$i]['hdate']}</span>
        </td>
        <td>
            <span style='font-size:9pt;'>{$row[$i]['hit']}</span>
        </td>
    </tr>

             ");                     
      }       
   }
   mysqli_close($conn);
?>

    <tr align='center'> 
        <td height="15" colspan="6" bgcolor="#0033CC"></td>
    </tr>      
	
    <form name="search" action="view.php" method="get">
    <tr align='left'>
            <td height="31" colspan="6" valign="middle">
                <p align="right">
                <span style="font-size:9pt;">
<?
  $p = $start - $scale;
  $n = $start + $scale;

  if($p>=0 && $login==1){
      echo(" <a href=./view.php?start=$p&search=$search>&lt;&lt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ");
  }
  else{
      echo(" &lt;&lt; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ");
  }

  if($n<$row_num && $login==1){
      echo("  <a href=./view.php?start=$n&search=$search>&gt;&gt;</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
  }
  else{
      echo(" &gt;&gt; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
  }

?>
                  <input type="text" name="search" size="20" class="inputBox" value="" maxlength="">
				  &nbsp;&nbsp;
				<input type="submit" name="확인" value="확인" style="font-size:9pt;">
				   &nbsp;&nbsp;&nbsp;&nbsp;				   
				</span>
				</p>
            </td>
    </tr>
    </form>
</table>
</body>
</html>
 