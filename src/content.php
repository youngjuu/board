<?
  session_start();
  require('mconn.php');
  require('login.php');

  $start = $_GET["start"];
  $search = $_GET["search"];
  $bno = $_GET["bno"];

  $mname = $_POST['mname'];
  $memo = $_POST['memo'];

  // 댓글 순번(mno)은 최근 게시물 + 1
  $sql = "select MAX(mno) from memo";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
  
  $mno = $row[0]["MAX(mno)"];
  if($mno == NULL){
      $mno = 0;
  }
  else{
      $mno = $mno+1;
  }

  // memo 테이블에 댓글 데이터 삽입
  if(!empty($mname)){
    $sql = "insert into memo(bno, mno, mname, mcontent)
            values($bno, $mno, '$mname', '$memo')";
    $result = mysqli_query($conn, $sql);
    
    if(!$result){
        echo("
            <script>
                window.alert('댓글 입력 실패');
                history.go(-1);
            </script>
            ");
        exit;
    }
  }

  // 조회수 1 증가
  $sql = "update board set hit= hit+1 where bno=$bno";
  $result = mysqli_query($conn, $sql);

  // 해당 게시물 내용 불러오기
  $sql = "select b.bno, id, subject, hdate, hit, content 
          from board b, content c
          where b.bno=c.bno and b.bno='$bno'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $content = $row[0]["content"];

  // 댓글 내용 불러오기
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>내용</title>
<style>
    a { text-decoration:none; } 
</style>
</head>

<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red">
<table width="607" align="center" bgcolor="white" bordercolordark="black" bordercolorlight="#333333" cellpadding="0" cellspacing="0">
    <tr>
        <td width="607" height="25" bordercolor="white" bgcolor="#0033FF" colspan="2">
            <p align="center"><span style="font-size:9pt;">&nbsp;</span></p>

        </td>
    </tr>
    <tr>
        <td width="94" height="72">
            <p align="right" style="line-height:17pt;">
			<b><font face="굴림" color="#000066"><span style="font-size:9pt;">
			제목 :<br>
            글쓴이 :<br>
            날짜 :<br>
			</span></font></b>
			</p>
        </td>
        <td width="513" height="72">
            <p align="left" style="line-height:17pt;">
			<span style="font-size:9pt;">

            &nbsp;<? echo("{$row[0]["subject"]}"); ?><br>
            &nbsp;<? echo("{$row[0]["id"]}"); ?> <br>
            &nbsp;<? echo("{$row[0]["hdate"]}"); ?> <br>
		</span></p>
        </td>
    </tr>
    <tr>
      <td width="607" height="7" bgcolor="#0033CC" colspan="2"></td>
    </tr>
    <tr valign="top">
      <td height="214" bgcolor="white" valign="top" width="607" bordercolor="white" colspan="2">           
           <p><span style="font-size:9pt;">&nbsp;</span></p>
           <p><span style="font-size:9pt;">
           <? echo("{$content}"); ?><br />
	       </span></p>
           <p>&nbsp;</p>            
       </td>
    </tr>
    <tr>
      <td width="607" height="7" bgcolor="#0033CC" colspan="2"></td>
    </tr>
    <tr valign="top">
        <td width="607" height="19" valign="top" colspan="2" bgcolor="white" bordercolor="white">
            <p align="right"><span style="font-size:9pt;">
    <?
    if($login==1 && $id==$row[0]["id"]){
        echo("
            <a href='./view.php?start=$start&search=$search'>[목록으로]</a>&nbsp;
            <a href='./del.php?bno=$bno'>[삭제]</a></span>&nbsp;</p>
            ");
    }
    else{
        echo("
            <a href='./view.php?start=$start&search=$search'>[목록으로]</a></span>&nbsp;</p>
            ");
    }
    ?>
        </td>
    </tr>

<?php
echo("
    <form name='content' method='post' action='content.php?bno=$bno&start=$start&search=$search'>
    ");
?>

    <tr valign="top">
        <td width="607" height="34" valign="middle" colspan="2" bgcolor="#CCCCCC" bordercolor="white" align="center">
            <p><span style="font-size:9pt;">이름 &nbsp;</span>
            <input type="text" name="mname" size="12" style="font-size:9pt;">
            <span style="font-size:9pt;"> &nbsp;&nbsp;댓글 &nbsp;</span>
            <input type="text" name="memo" style="font-size:9pt;" size="54">
            <span style="font-size:9pt;"> &nbsp;</span>
            <input type="submit" name="formbutton1" value="쓰기" style="font-size:9pt;"></p>
        </td>
    </tr>
    </form>
    <tr valign="top">
        <td width="607" height="65" align="center" valign="middle" colspan="2" bgcolor="#CCCCCC" bordercolor="white">
          <table width="605" cellpadding="0" cellspacing="0">
              <tr>

<?php // memo 테이블에서 이름, 댓글 불러오기
    $sql = "select mname, mcontent from memo
            where bno=$bno
            order by mno desc";
    $result = mysqli_query($conn, $sql);
    $row_num = mysqli_num_rows($result);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if($row_num > 0){
        for($i=0; $i<$row_num; $i++){
            echo("
                <tr>
                    <td width='68'>
                        <p align='center'><span style='font-size:9pt;'>{$row[$i]['mname']}</span></p>
                    </td>
                    <td style='word-break:break-all;' width='521'><span style='font-size:9pt;'>{$row[$i]['mcontent']}</span>
                    </td>
                </tr>
                ");
        }
    }
?>

          </table>
        </td>
    </tr>
</table>
</body>
</html>