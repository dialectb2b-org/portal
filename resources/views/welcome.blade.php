<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
        }

        .container {
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
            margin: 10px 0; 
        }

    </style>
    <div class="container">
        <img src="{{ asset('coming-soon-ico.svg') }}" alt="Coming Soon"><br>
        <img src="{{ asset('coming-soon.svg') }}" alt="Coming Soon">
        <h1>...</h1>
    </div>
</body>
</html>
