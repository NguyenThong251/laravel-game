<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game record pull</title>
</head>
<body>
            <div style="width: 400px;height:100px;margin: auto">
                <p>Total records</p>
                <iframe src="{{ route('admin.gamerecord.pull') }}" width="100%" height="100%" frameborder="0"></iframe>
            </div>
</body>
</html>