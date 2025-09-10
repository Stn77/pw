<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @vite('resources/js/bootstrap.js')
    <script>
        setTimeout(() => {
            window.Echo.chanel('testCahnel')
            .listen('TestingEvent', (e) => {
                console.log(e)
            })
        }, 200);
    </script>
</body>
</html>
