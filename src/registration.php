<?php
require 'db-connect.php';

// カテゴリ一覧をデータベースから取得
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $categoryStmt = $pdo->prepare("SELECT category_id, category_name FROM categori");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}

// フォームが送信されたか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 入力されたデータを取得
        $name = $_POST['name'];
        $artist_name = $_POST['artist_name'];
        $category_id = $_POST['category_id'];
        $release_date = $_POST['release_date'];
        $image_path = $_POST['image_path']; // ここを画像のURLに変更
        $lyrics = $_POST['lyrics'];

        // データベースに接続
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // artist テーブルにアーティスト名を挿入
        $artistStmt = $pdo->prepare("INSERT INTO artist (artist_name) VALUES (?)");
        $artistStmt->bindParam(1, $artist_name);
        $artistStmt->execute();

        // artist テーブルから挿入したアーティストのIDを取得
        $artistIdStmt = $pdo->prepare("SELECT artist_id FROM artist WHERE artist_name = ?");
        $artistIdStmt->bindParam(1, $artist_name);
        $artistIdStmt->execute();
        $artistId = $artistIdStmt->fetchColumn();

        // データベースに新規データを挿入
        $stmt = $pdo->prepare("
            INSERT INTO music (name, artist_id, category_id, release_date, image_path, lyrics)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $artistId);
        $stmt->bindParam(3, $category_id);
        $stmt->bindParam(4, $release_date);
        $stmt->bindParam(5, $image_path);
        $stmt->bindParam(6, $lyrics);

        $stmt->execute();

        echo "データが新規登録されました。";
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
</head>
<body>
<header>
    <nav>
        <a href="home.php">ホーム</a>
        <a href="registration.php">新規登録</a>
    </nav>
</header>
    <h1>新規登録</h1>
    <form method="post">
        <label for="name">曲名:</label>
        <input type="text" name="name" required><br>

        <label for="artist_name">アーティスト名:</label>
        <input type="text" name="artist_name" required><br>

        <label for="category_id">カテゴリ:</label>
        <!-- カテゴリのドロップダウンリスト -->
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="release_date">リリース日:</label>
        <input type="date" name="release_date" required><br>

        <label for="image_path">画像URL:</label>
        <input type="text" name="image_path" required><br>

        <label for="lyrics">歌詞:</label>
        <textarea name="lyrics" required></textarea><br>

        <button type="submit">登録</button>
    </form>
</body>
</html>
