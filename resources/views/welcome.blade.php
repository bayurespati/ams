<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Landing Page</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f0f4f8, #dbe9f4);
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        .logo {
            width: 150px;
            height: 150px;
            object-fit: contain;
            border-radius: 20%;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        h1 {
            font-size: 2em;
            color: #333;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="/logo/logo.png" alt="Logo" class="logo" />
        <h1>PINS INDONESIA</h1>
    </div>

    <script>
        // Example: You could later add form, tracking, etc. here
        console.log('Landing page loaded.');
    </script>
</body>

</html>