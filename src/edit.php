<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集</title>
</head>
<body>
<header>
    <nav>
        <a href="home.php">ホーム</a>
        <a href="registration.php">新規登録</a>
    </nav>
</header>
    <?php
    // データベースからカテゴリの一覧を取得するなどのコードを追加

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        try {
            // データベースに接続
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // IDに対応するデータを取得
            $stmt = $pdo->prepare("SELECT m.id, m.name, m.artist_id, a.artist_name, m.category_id, c.category_name, m.release_date, m.lyrics FROM music m JOIN artist a ON m.artist_id = a.artist_id JOIN categori c ON m.category_id = c.category_id WHERE m.id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();

            $imageData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($imageData) {
                // カテゴリ一覧を取得
                $categories = $pdo->query("SELECT category_id, category_name FROM categori")->fetchAll(PDO::FETCH_ASSOC);

                // 編集フォームの表示
                ?>
                <h1>編集</h1>
                <form method="post" action="update.php">
                    <input type="hidden" name="id" value="<?php echo $imageData['id']; ?>">
                    <label for="name">曲名:</label>
                    <input type="text" name="name" value="<?php echo $imageData['name']; ?>" required><br>
                    <label for="artist_name">アーティスト名:</label>
                    <input type="text" name="artist_name" value="<?php echo $imageData['artist_name']; ?>" required><br>
                    <label for="category_id">カテゴリ:</label>
                    <select name="category_id" required>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $imageData['category_id']) ? 'selected' : ''; ?>>
                                <?php echo $category['category_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                    <!-- 他の入力フィールドも同様に表示 -->
                    <button type="submit">更新</button>
                </form>
                <?php
            } else {
                echo "指定されたIDのデータが見つかりませんでした。";
            }
        } catch (PDOException $e) {
            echo "エラー: " . $e->getMessage();
        }
    } else {
        echo "IDが指定されていません。";
    }
    ?>
</body>
</html>
