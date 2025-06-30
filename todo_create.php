<?php
// POSTデータ確認
// var_dump($_POST);
// exit();

if(!isset($_POST['todo']) ||// 必須項目（todo）のデータが送信されていない
   $_POST['todo'] === '' ||// 必須項目（todo）が空で送信されている
   !isset($_POST['deadline']) ||// 必須項目（deadline）のデータが送信されていない
   $_POST['deadline'] === ''){// 必須項目（deadline）が空で送信されている
    exit('送信するデータがありません');//条件に合致する場合は以降の処理を中止してエラー画面を表示
}

$todo = $_POST['todo'];
$deadline = $_POST['deadline'];

// 各種項目設定
$dbn ='mysql:dbname=gs_dev_php;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}
// 「dbError:...」が表示されたらdb接続でエラーが発生していることがわかる

// SQL作成&実行
$sql = 'INSERT INTO todo_table (id, todo, deadline, created_at, updated_at) VALUES (NULL, :todo, :deadline, now(), now())';

$stmt = $pdo->prepare($sql);
// バインド変数を設定
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL が正常に実行された場合は，データ入力画面に移動する
header('Location:todo_input.php');
exit();