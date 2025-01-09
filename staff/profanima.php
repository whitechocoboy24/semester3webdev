<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="load.css">
</head>

<body>
    <div class="container">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <h1 id="h1">Please wait...</h1>
    <h2 id="h2" style="display: none;"></h2>

    <script>
        // Show h2 after 1 second
        setTimeout(function() {
            document.getElementById("h1").style.display = "none";
            document.getElementById("h2").style.display = "block";
        }, 1000);

        // Redirect to staff.php after 2 seconds
        setTimeout(function() {
            window.location.href = 'profile.php';
        }, 500);
    </script>
</body>

</html>

