<!doctype html>
<html lang="ja">
<html>
    
<head>
    
    <meta charset="UTF-8">
    <title>mission_5-1</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        
<style>

h1{
	position:relative;
	line-height:2em;
	padding-left:3em;
}
h1:before{
	position:absolute;
	left:0;
	content:"";
	display:inline-block;
	width:2em;
	height:2em;
	background:url(hito.jpeg) no-repeat;
	background-size:contain;
	top: 5px;
}

h2{
	background-color:silver; /* 背景色 */
	color:black; /* 文字色 */
	overflow: hidden;
	padding: 3px;  /* 余白 */
	position: relative;
    margin: auto;
    border-radius: 10px 10px 10px 10px;
}
h2:before{
	background-color: #fff; /* 切り替わる色1 */
	content: '';
	display: block;
	opacity: 0.3; /* 不透明度 */
	transform: rotate(-50deg);
	position: absolute;
	bottom: -10px;
	right: -330px;
	width: 500px;
	height: 500px;
}
h2:after{
	background-color: #fff; /* 切り替わる色2 */
	content: '';
	display: block;
	opacity: 0.3; /* 不透明度 */
	transform: rotate(-70deg);
	position: absolute;
	bottom: -100px;
	right: -500px;
	width: 500px;
	height: 500px;
}

body {
    padding-top: 90px; /* ヘッダーの後ろに要素が隠れないようにするため */
    text-align: center;
    background-image: url(beige.jfif);
    font-family: serif;
   }

.bg{
   opacity: 0.68; /* 55％不透明度(＝45％透過) */
    background-color: snow;
    color:black;
    width: 60%;
    margin: auto;
    border-radius: 20px 20px 20px 20px;/*左側の角丸く*/
}

header {
    z-index: 10;
    width: 100%; /* 幅いっぱいを指定 */
    height: 50px; /* 高さを50pxに指定 */
    padding: 30px 50px; /* ヘッダーに上下左右それぞれ余白を指定 */
    box-sizing: border-box; /* padding分を含んで幅を100%にするため */
    position: fixed; /* ウィンドウを基準に画面に固定 */
    top: 0; /* 上下の固定位置を上から0pxにする */
    left: 0; /* 左右の固定位置を左から0pxにする */
    display: flex; /* 中の要素を横並びにする */
    align-items: center; /* 中の要素を上下中央に並べる */
    background-color:rosybrown;
    color: whitesmoke;
}

main {
    height: 100vw; /* スクロールの演出を見れるようにmainに高さを指定 */
}

img {
	z-index: 100;
}

</style>

</head>

<body>
    
<header>
<h1>簡易掲示板</h1>
</header>

<?php

try{
//DB接続設定
$dsn = 'mysql:dbname=DBNAME;host=localhost';
$user = 'USER';
$password = 'PASSWORD';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//PHPとDBの接続を行うPDOクラスをインスタンス化

//テーブル作成
$sqlCreateTable="CREATE TABLE IF NOT EXISTS board"
." ("
. "id int auto_increment primary key,"
. "name char(32),"
. "comment TEXT,"
. "date TEXT,"
. "password TEXT"
.");";
$stmtCreateTable = $pdo->query($sqlCreateTable);

//データ追加準備
$sqlInsert="INSERT INTO board(name,comment,date,password) VALUES (:name,:comment,:date,:password)";
$stmtInsert=$pdo->prepare($sqlInsert);
$stmtInsert -> bindParam(':name', $name, PDO::PARAM_STR);
$stmtInsert -> bindParam(':comment', $comment, PDO::PARAM_STR);
$stmtInsert -> bindParam(':date', $date, PDO::PARAM_STR);
$stmtInsert -> bindParam(':password', $pw, PDO::PARAM_STR);

//データ削除準備
$sqlDelete = 'DELETE FROM board WHERE id=:id';
$stmtDelete = $pdo->prepare($sqlDelete);
$stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);

//データ更新準備
$sqlUpdate = 'UPDATE board SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
$stmtUpdate = $pdo->prepare($sqlUpdate);
$stmtUpdate -> bindParam(':id', $id, PDO::PARAM_STR);
$stmtUpdate -> bindParam(':name', $name, PDO::PARAM_STR);
$stmtUpdate -> bindParam(':comment', $comment, PDO::PARAM_STR);
$stmtUpdate -> bindParam(':date', $date, PDO::PARAM_STR);
$stmtUpdate -> bindParam(':password', $pw, PDO::PARAM_STR);

if(isset($_POST["name"])&&isset($_POST["comment"])) {//全項目がPOST送信されたとき
    if ($_POST["name"] != ""&&$_POST["comment"] != ""){//送信された値が空値でないとき
        
        $name=$_POST["name"];    
        $comment=$_POST["comment"];
        $date=date("Y年m月d日 H:i:s");
        $pw=$_POST["password"];
        $edit=$_POST["edit"];
        
            if ($edit != ""){//"edit"欄が空値でないとき
                 
            $id=$edit;
            $stmtUpdate -> execute();
                    
            } else {//空値のとき
                    
             $stmtInsert -> execute();       
                    
            }       
    }
}

if(isset($_POST["delnum"])&&isset($_POST["password_2"])) {//POST送信されたとき
    if ($_POST["delnum"] != ""&&$_POST["password_2"] != ""){//送信された値が空値でないとき

        $delnum=$_POST["delnum"];  
        $pw2=$_POST["password_2"];

        $sqlSelect = 'SELECT * FROM board';
        $stmtSelect = $pdo->query($sqlSelect);
        $results = $stmtSelect->fetchAll();

            foreach ($results as $row){
            
               if($row['id']==$delnum&&$row['password']==$pw2){
                   
                   $id=$delnum;
                   $stmtDelete -> execute();
                   
               }
                
            }    
        
    }
}

if(isset($_POST["editnum"])&&isset($_POST["password_3"])) {//POST送信されたとき
    if ($_POST["editnum"] != ""&&$_POST["password_3"] != ""){//送信された値が空値でないとき
   
          $editnum=$_POST["editnum"];
          $pw3=$_POST["password_3"];
          
          $sqlSelect = 'SELECT * FROM board';
          $stmtSelect = $pdo->query($sqlSelect);
          $results = $stmtSelect->fetchAll();

            foreach ($results as $row){
    
                            if($row["id"]==$editnum&&$row["password"]==$pw3){
                                
                                $editname=$row["name"];
                                $editcomment=$row["comment"];
                                $editpw=$row["password"];
                                
                            }
            }
   
    }
}

//表示
$sqlSelect = 'SELECT * FROM board';
$stmtSelect = $pdo->query($sqlSelect);
$results = $stmtSelect->fetchAll();

    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
        echo "<hr>";
    }

} catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
}
?>

<br>
<div class="bg">
    
    <h2>投稿欄</h2>
<br>
        <form action="#" method="post">
                <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
                <input type="text" placeholder="名前" name="name" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
                <i class="fa fa-comment fa-lg fa-fw"></i>
                <input type="text" placeholder="コメント欄" name="comment" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
                <i class="fa fa-lock fa-lg fa-fw"></i>
                <input type="password" placeholder="パスワード" name="password" value="<?php if(isset($editpw)) {echo $editpw;} ?>" ><br>
                <input type="hidden" name="edit" value="<?php if(isset($editnum)) {echo $editnum;} ?>">
                <input type="submit">
                
        </form>
<br>

</div>

<br>

<div class="bg">
    <h2>削除欄</h2>
<br>
        <form action="#" method="post">
                <i class="fa fa-times-circle fa-lg fa-fw"></i>
                <input type="number" placeholder="削除対象番号" name="delnum" >
                <br>
                <i class="fa fa-lock fa-lg fa-fw"></i>
                <input type="password" placeholder="パスワード" name="password_2" >
                <br>
                <input type="submit" value="削除">
        
        </form>
<br>
</div>

<br>

<div class="bg">
    <h2>編集欄</h2>
<br>
        <form action="#" method="post">
                
                <i class="fa fa-edit fa-lg fa-fw"></i>
                <input type="number" placeholder="編集対象番号" name="editnum" ><br>
                <i class="fa fa-lock fa-lg fa-fw"></i>
                <input type="password" placeholder="パスワード" name="password_3" ><br>
                <input type="submit" value="編集">
                
        </form>
<br>
</div>

</body>
</html>