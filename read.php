<?php
require_once('funcs.php');

// DB接続
$pdo = db_conn();

// SQL作成&実行
$sql = 'SELECT * FROM records ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// データ取得
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

function conditionText($num) {
  if ($num >= 80) return "良い ({$num})";
  if ($num >= 60) return "やや良い ({$num})";
  if ($num >= 40) return "まあまあ ({$num})";
  if ($num >= 20) return "やや悪い ({$num})";
  return "悪い ({$num})";
}

// HTML生成
$elements = '';
foreach ($results as $record) {
  $elements .= "
    <div class='card'>
      <div class='card-header'>{$record['record_date']} {$record['record_time']} - {$record['record_type']}</div>
      <div class='card-body'>
        <p><strong>ニックネーム:</strong> {$record['nickname']}</p>
        <p><strong>体調:</strong> " . conditionText($record['body_condition']) . "</p>
        <p><strong>心調:</strong> " . conditionText($record['mental_condition']) . "</p>
        <p><strong>やりたい/やったこと:</strong> {$record['want_to_do']}</p>
        <p><strong>ひとこと:</strong> {$record['memo']}</p>
        <p><strong>天気:</strong> {$record['weather']}</p>
      </div>
    </div>
  ";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>今までのきろく一覧</title>
  <style>
    body {
      background: #f5f5f5;
      padding: 20px;
    }
    fieldset {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border: none;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    legend {
      font-size: 1.2em;
      font-weight: bold;
      margin-bottom: 10px;
    }
    a {
      display: inline-block;
      margin-bottom: 15px;
      color: #007bff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .card {
      background: #fdfdfd;
      border: 1px solid #ddd;
      border-radius: 6px;
      margin-bottom: 15px;
      box-shadow: 0 0 3px rgba(0,0,0,0.05);
    }
    .card-header {
      background: #e9ecef;
      padding: 8px 12px;
      font-weight: bold;
      border-bottom: 1px solid #ddd;
    }
    .card-body {
      padding: 10px 12px;
    }
    .card-body p {
      margin: 4px 0;
    }
  </style>
</head>
<body>
  <fieldset>
    <legend>今までのきろく一覧</legend>
    <a href="input.php">入力画面に戻る</a>
    <?= $elements ?>
  </fieldset>
</body>
</html>