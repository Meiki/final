<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム</title>
</head>
<body>
<header>
    <nav>
        <a href="home.php">ホーム</a>
        <a href="registration.php">新規登録</a>
    </nav>
</header>
    <?php
    try {
        // データベースに接続
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // IDが2から始まる5つの画像の情報をデータベースから取得
        $stmt = $pdo->prepare("SELECT id, image_path, name FROM music WHERE id >= ? ");
        $startingId = 2; // 開始のID
        $stmt->bindParam(1, $startingId);
        $stmt->execute();

        $imageData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($imageData as $data) {
            $id = $data['id'];
            $imagePath = $data['image_path'];
            $imageName = $data['name'];

            // 各画像に対するリンク
            echo "<a href='detail.php?id=$id'>";
            echo "<img src='$imagePath' alt='$imageName'>";
            echo "</a>";
            echo "<p>曲名: $imageName</p>";
            echo "<hr>"; // 各データの区切り線
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
    ?>
</body>
</html>
