<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細</title>
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
        // idがGETパラメータとして渡されたか確認
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // データベースに接続
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // IDに対応するデータを取得
            $stmt = $pdo->prepare("
                SELECT m.id, m.name, m.artist_id, a.artist_name, m.category_id, c.category_name,
                       m.release_date, m.image_path, m.lyrics
                FROM music m
                JOIN artist a ON m.artist_id = a.artist_id
                JOIN categori c ON m.category_id = c.category_id
                WHERE m.id = ?
            ");
            $stmt->bindParam(1, $id);
            $stmt->execute();

            $imageData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($imageData) {
                // 取得したデータを表示
                echo "<img src='{$imageData['image_path']}' alt='{$imageData['name']}'>";
                echo "<p>曲名: {$imageData['name']}</p>";
                echo "<p>アーティスト: {$imageData['artist_name']}</p>";
                echo "<p>カテゴリ: {$imageData['category_name']}</p>";
                echo "<p>リリース日: {$imageData['release_date']}</p>";
                echo "<p>歌詞: {$imageData['lyrics']}</p>";

                // 編集ボタンを表示
                echo "<form method='get' action='edit.php'>";
                echo "<input type='hidden' name='id' value='{$id}'>";
                echo "<button type='submit' name='edit'>編集</button>";
                echo "</form>";

                // 削除ボタンを表示
                echo "<form method='post'>";
                echo "<input type='hidden' name='id' value='{$id}'>";
                echo "<button type='submit' name='delete'>削除</button>";
                echo "</form>";

                // 削除ボタンが押された場合の処理
                if (isset($_POST['delete'])) {
                    // 実際の削除処理はここに記述
                    // 削除の前に十分な検証とセキュリティ対策を行うことが重要
                    // 以下は簡単な例として、単純にデータを削除する処理
                    $deleteStmt = $pdo->prepare("DELETE FROM music WHERE id = ?");
                    $deleteStmt->bindParam(1, $id);
                    $deleteStmt->execute();

                    echo "<p>データが削除されました。</p>";
                }
            } else {
                echo "指定されたIDのデータが見つかりませんでした。";
            }
        } else {
            echo "IDが指定されていません。";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
    ?>
</body>
</html>
