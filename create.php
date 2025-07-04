<?php
require_once('funcs.php');

// 必須データ確認
if (
  !isset($_POST['record_type']) || $_POST['record_type'] === '' ||
  !isset($_POST['body']) || $_POST['body'] === '' ||
  !isset($_POST['mental']) || $_POST['mental'] === ''
) {
  exit('必要なデータが送信されていません');
}

// データ受け取り
$record_type = $_POST['record_type'];
$nickname = $_POST['nickname'];
$body = $_POST['body'];
$mental = $_POST['mental'];
$memo = $_POST['memo'];
$weather = $_POST['weather'];

// チェックボックスの値を文字列にまとめる
$want_to_do = '';
if(isset($_POST['want_to_do']) && is_array($_POST['want_to_do'])){
  $want_to_do = implode(',', $_POST['want_to_do']);
}

// 日付・時刻
$record_date = date('Y-m-d');
$record_time = date('H:i:s');
$created_at = date('Y-m-d H:i:s');

// DB接続
$pdo = db_conn();

// SQL作成
$sql = 'INSERT INTO records (record_date, record_time, record_type, nickname, weather, body_condition, mental_condition, want_to_do, memo, created_at)
        VALUES (:record_date, :record_time, :record_type, :nickname, :weather, :body, :mental, :want_to_do, :memo, :created_at)';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':record_date', $record_date, PDO::PARAM_STR);
$stmt->bindValue(':record_time', $record_time, PDO::PARAM_STR);
$stmt->bindValue(':record_type', $record_type, PDO::PARAM_STR);
$stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
$stmt->bindValue(':weather', $weather, PDO::PARAM_STR);
$stmt->bindValue(':body', $body, PDO::PARAM_INT);
$stmt->bindValue(':mental', $mental, PDO::PARAM_INT);
$stmt->bindValue(':want_to_do', $want_to_do, PDO::PARAM_STR);
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
$stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

// SQL実行
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// 入力画面に戻る
header('Location:input.php');
exit();
?>