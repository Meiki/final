<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム</title>
</head>
<body>
<form method="post">
    <!-- ボタンの作成 -->
    <button type="submit" name="home">ホーム</button>
    <button type="submit" name="registration">登録</button>
    <a href="detail.php">
        <img src="../image/newjeans-attention.png" alt="NO image!">
    </a>
    <?php
try {
    // データベースに接続
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // IDが2の画像をデータベースから取得
    $stmt = $pdo->prepare("SELECT image_path FROM music WHERE id = ?");
    $imageId = 2; // ここを指定のIDに変更
    $stmt->bindParam(1, $imageId);
    $stmt->execute();

    $imageData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($imageData) {
        $imagePath = $imageData['image_path'];
        echo "<a href='$imagePath' target='_blank'><img src='$imagePath' alt='画像'></a>";
    } else {
        echo "指定されたIDの画像が見つかりませんでした。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

</form>
</body>
</html>