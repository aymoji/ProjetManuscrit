<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            background-color: white;
            color: #003366; 
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #003366; 
            margin-top: 50px;
        }

        p {
            font-size: 18px;
            color: #003366;
        }

        button {
            background-color: #003366;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #00509e;
        }
    </style>
</head>
<body>
    <h1>Bienvenue</h1>
    <p>Veuillez choisir une option :</p>
    <button onclick="location.href='{{ route('login') }}'">Login</button>
    <button onclick="location.href='{{ route('register') }}'">Sign in</button>
</body>
</html>
