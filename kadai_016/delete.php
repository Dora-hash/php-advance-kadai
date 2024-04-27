<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // idカラムの値を(:id)に置き換えたSQL文をあらかじめ用意
    $sql_delete = 'DELETE FROM books WHERE id = :id';

    // 実際の値をプレースホルダにバインド
    $stmt_delete = $pdo->prepare($sql_delete);

    // SQL文を実行
    $stmt_delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    // 削除した件数を取得
    $stmt_delete->execute();
    $count = $stmt_delete->rowCount();
    $message = "商品を{$count}件削除しました。";

    // 書籍一覧ページにリダイレクト。messageパラメータも渡す。
    header("Location: read.php?message={$message}");
} catch(PDOException $e){
    exit($e->getMessage());
}
?>