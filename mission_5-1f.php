<html>
<?php

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

?>

<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
	<meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
	
</head>
<titel><font size ="5"> mission_5-1</font> </title>
<hr>
<body>
<form action ="mission_5-1e.php" method ="post">
<?php
$edit = "";
$edit_num = "";
$edit_name ="";
$edit_comment ="";
$edit_pass ="";

if (!empty($_POST["edit_num"])){
	$edit_num = $_POST['edit_num'];
	$edit_pass = $_POST['edit_pass'];
//	var_dump($edit_num);
//	var_dump($edit_pass);

$sql = 'SELECT :id, :name, :comment, :password FROM m5 where id=:id AND password=:password';
	$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':id', $edit_num, PDO::PARAM_INT);
		$stmt->bindParam(':password', $edit_pass, PDO::PARAM_INT);
		$stmt->bindParam(':name', $edit_name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $edit_comment, PDO::PARAM_STR);
		$stmt->execute();

	$sql = 'SELECT * FROM m5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		if($row['id'] == $edit_num){
				$edit_name= $row['name'];
				$edit_comment= $row['comment'];
			//	var_dump($edit_name);
			//	var_dump($edit_comment);
		}
	}
}
?>


<!-- フォーム作成-->
【投稿フォーム】<br>
<label for"rendou1">名前　　　:
<!-- 編集があれば表示 -->
<input id="rendou1" type="text" name ="name"  placeholder="名前" 
value="<?php if (isset($edit_num)){
				echo $edit_name;
				} else{ 
				echo ""; 
				}?>"/><br /> 
<label for"rendou2">コメント　:
<input id="rendou2" type="text" name="comment" placeholder="コメント"
value="<?php if (isset($edit_num)){
				echo $edit_comment;
				} else{ 
				echo ""; 
				}?>"/><br />
<label for"r5">パスワード:
<input id="r5" type="text" name="submit_pass" placeholder = "パスワード" value=""><br />

<!--編集番号フラグ hiddenにする	-->
<input type="hidden" name="edit" value="<?php echo $edit_num; ?>">

<p><input type="submit" value="投稿"></p>
	<!-- type="text", type="number", type="email"なども使える-->
	<!-- text area は複数行入力か。終了タグが必要！！valueは使用不可-->
	<!--input要素に「required」属性を追加するだけでエラーメッセージを表示する事ができる-->
<br>
【削除フォーム】<br>
<label for"r3">削除番号　:
<input id="r3" type="text" name ="delete_num" placeholder="削除番号" value="" /><br />
<label for"r6">パスワード:
<input id="r6" type="text" name="delete_pass" placeholder="パスワード" value=""><br />
<p><input type="submit" value="削除"></p>
<br>
【編集フォーム】<br>
<label for"r4">編集番号　:
<input id="r4" type="text" name ="edit_num" placeholder="編集番号" value="" /><br />
<label for"r7">パスワード:
<input id="r7" type="text" name="edit_pass" placeholder="パスワード" value=""><br />

<p><input type="submit" value="編集"></p>
<hr>
</form>

<?php
echo "<br>";
//echo "mission5 databaseに接続";
echo "<br>";
$sql = "CREATE TABLE IF NOT EXISTS m5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);

$name = "";
$comment = "";
$date = "";
$num = "";
$array = "";
$submit_pass="";


if (!empty($_POST["name"])&&!empty($_POST["comment"])){

	if (!empty($_POST["edit_num"])){
		$edit_name = $_POST['name'];
		$edit_comment = $_POST['comment'];
		$edit_pass = $_POST['submit_pass'];
		$sql = 'update m5 set name=:name,comment=:comment where id=:id AND password=:password';
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':name', $edit_name, PDO::PARAM_STR);
		$stmt->bindValue(':comment', $edit_comment, PDO::PARAM_STR);
		$stmt->bindValue(':id', $edit_num, PDO::PARAM_INT);
		$stmt->bindValue(':password', $edit_pass, PDO::PARAM_INT);
		$stmt->execute();
	
	}else{
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$submit_pass = $_POST['submit_pass'];

		$sql = $pdo -> prepare("INSERT INTO m5 (name, comment, password) VALUES (:name, :comment, :password)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':password', $submit_pass, PDO::PARAM_STR);
		$sql -> execute();
	}
}

if (!empty($_POST["delete_num"])){

	$delete_num = $_POST['delete_num'];
	$delete_pass = $_POST['delete_pass'];
	
	$sql = 'delete from m5 where id=:id AND password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $delete_num, PDO::PARAM_INT);
	$stmt->bindParam(':password', $delete_pass, PDO::PARAM_STR);
	$stmt->execute();

}

$sql ='SHOW CREATE TABLE m5';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";


$sql = 'SELECT * FROM m5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
echo "<hr>";
}

?>

</body>
</html>



