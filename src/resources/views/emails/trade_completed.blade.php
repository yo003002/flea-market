<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取引完了</title>
</head>
<body>
    <h2>取引が完了しました</h2>
    <p>{{ $purchase->user->name }}さんが取引完了しました。</p>
    <p>商品名：{{ $purchase->item->title }}</p>
    <p>評価をお願いします。</p>
</body>
</html>