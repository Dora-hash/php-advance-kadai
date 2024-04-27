<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // orderパラメータの値が存在（並び替えボタンを押したとき）値を変数$orderに代入。
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    }else {
        $order = NULL;
    }

    // keywordパラメータの値が存在（並び替えボタンを押したとき）値を変数$keywordに代入。
    if (isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }
    
    // orderパラメータの値により、SQL文を変更。
    if ($order === 'desc') {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC';
    } else {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
    }

    // SQL文を用意。
    $stmt_select = $pdo->prepare($sql_select);

    // SQLのLIKE文で使用。
    $partial_match = "%{$keyword}%";

    // 値をプレースホルダにバインド
    $stmt_select->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

    // SQL文の実行
    $stmt_select->execute();

    // 実行結果を配列で取得
    $books = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    exit($e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍一覧</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- GoogleFontsの読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet"></head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="books">
            <h1>書籍一覧</h1>
            <?php
            // 書籍の登録・更新・削除後messageパラメータの値を受け取っていれば表示
            if (isset($_GET['message'])){
                echo "<p class='success'>{$_GET['message']}</p>";
            }
            ?>

            <div class="books_ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="images/desc.png" alt="降順に並び替え" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="images/asc.png" alt="昇順に並び替え" class="sort-img">
                    </a>
                    <form action="read.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" class="search-box" placeholder="書籍名で検索" name="keyword" value="<?= $keyword ?>">
                    </form>
                </div>
                <a href="create.php" class="btn">書籍登録</a>
            </div>
            <table class="books_table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                // 配列の中身を順に取り出す。→表形式で出力。
                foreach ($books as $book){
                    $table_row = "
                        <tr>
                            <td>{$book['book_code']}</td>
                            <td>{$book['book_name']}</td>
                            <td>{$book['price']}</td>
                            <td>{$book['stock_quantity']}</td>
                            <td>{$book['genre_code']}</td>
                            <td><a href='update.php?id={$book['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                            <td><a href='delete.php?id={$book['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>
                        </tr>
                    ";
                    echo $table_row;
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 書籍管理アプリ All rights reserved.</p>
    </footer>
</body>
</html>