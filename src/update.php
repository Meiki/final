<?php
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // データベースに接続
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // POST データから受け取った値を取得
        $id = $_POST['id'];
        $name = $_POST['name'];
        $artist_name = $_POST['artist_name'];
        $category_id = $_POST['category_id']; // 新しく追加
        // 他のフィールドも同様に取得

        // データベースのレコードを更新
        $stmt = $pdo->prepare("
            UPDATE music
            SET name = ?, artist_id = (SELECT artist_id FROM artist WHERE artist_name = ?),
                category_id = ?, release_date = ?, lyrics = ?
            WHERE id = ?
        ");

        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $artist_name);
        $stmt->bindParam(3, $category_id); // 新しく追加
        // 他のフィールドも同様にバインド
        $stmt->bindParam(4, $release_date);
        $stmt->bindParam(5, $lyrics);
        $stmt->bindParam(6, $id); // WHERE 句に対応する ID を指定

        $stmt->execute();

        // 更新が成功した場合、ホーム画面にリダイレクトするなどの処理を追加
        header("Location: home.php");
        exit();
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "不正なアクセスです。";
}
?>
