<meta charset="UTF-8">
<?
	$conn = mysqli_connect('49.50.164.99:3306', 'choi', '1234') or die('Could not connect: ');
	mysqli_select_db($conn, "project01");
	mysqli_set_charset($conn, "utf8");
?>