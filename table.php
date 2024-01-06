<?php
session_start();
if (!isset($_SESSION["user"])) {

    header("Location: registration.html");
} else {
    $table = $_GET["table"];
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>search car table</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/tableStyle.css">
</head>


<body>
    <div class="navbar">
        <a class="active" href="#"><i class="fa fa-fw fa-home"></i> Home</a>
        <a href="#"><i class="fa fa-fw fa-search"></i> Search</a>
        <a href="#"><i class="fa fa-fw fa-envelope"></i> Contact</a>
        <a href="#"><i class="fa fa-fw fa-user"></i> Login</a>
    </div>
    <h1></h1>

    <!-- <label for="id">car name</label>
    <input type="text" id="id">
    <br>
    <button id="search" type="button">Search</button> -->


    <!-- <embed type="text/html" src="registration.html" width="500" height="200"> -->
    <div id="leftContainer">
        <div id="res"></div>
    </div>

    <div id="rightContainer"></div>

    <script>
        $(document).ready(function() {

            var tableName = <?php echo json_encode($table, JSON_HEX_TAG); ?>;

            $("h1").html(titleCase(tableName) + " Table");

            $("#search").click(function() {

                $.ajax({
                    url: "php/searchService.php",
                    type: "POST",
                    data: {
                        table: tableName,
                    },
                    success: function(data) {
                        $("#res").html(data);
                    }
                });
            });
        });



        function titleCase(st) {
            return st.toLowerCase().split(" ").reduce((s, c) =>
                s + "" + (c.charAt(0).toUpperCase() + c.slice(1) + " "), '');
        }
    </script>

</body>

</html>