<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wshare Terms of Service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles specific to the Terms of Service page */
        body {
            font-family: inter;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-size: 30px;
            margin-bottom: 30px;
        }

        p {
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">

        <h1>Wshare Terms of Service</h1>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et sem justo. Vestibulum dapibus dapibus est, nec pulvinar nisi sollicitudin a. Quisque iaculis interdum gravida. Nulla facilisi. Ut et viverra nulla. Sed aliquam sem a ante hendrerit, id ullamcorper est fringilla. Ut ullamcorper metus quis mauris fringilla varius. Nulla facilisi. Donec imperdiet, libero vel interdum aliquam, felis mauris posuere metus, non euismod sem metus non leo. Vivamus elementum massa sit amet ex vehicula dapibus. Aliquam placerat convallis tellus, vitae dictum lorem laoreet nec.</p>

        <p>Quisque nec lorem ac est accumsan finibus. Duis ultricies dui nec eros elementum, ac dictum lacus cursus. Sed sagittis ultricies libero id malesuada. Pellentesque consequat justo at massa tincidunt, sed placerat purus tincidunt. Cras sit amet convallis ligula. Integer id tempus odio. Fusce rhoncus libero ut felis vestibulum, non vulputate ipsum malesuada.</p>

        <p>Vivamus in odio risus. Morbi non arcu fermentum, suscipit libero eu, interdum nunc. Vivamus euismod consequat velit, sit amet facilisis lectus. Ut quis justo in risus convallis ultricies. Donec egestas elementum eros, sed sodales dui. Donec vehicula consequat leo, in faucibus nisi mollis id. Phasellus sit amet nunc vel eros posuere tempor vel nec urna.</p>

        <!-- Back button -->
        <a id = "backButton" class="btn">Back to Sign Up</a>

    </div>

    <script>
        // JavaScript to handle back button functionality
        document.getElementById('backButton').addEventListener('click', function() {
            // Go back in browser history
            window.history.back();
        });
    </script>
</body>
</html>
