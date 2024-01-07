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
    <link rel="icons" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/tableStyle.css">

</head>


<body>
    <div class="navbar">
        <ul id="tabList">
            <li><a class="active" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>

        </ul>
    </div>


    <!-- <embed type="text/html" src="registration.html" width="500" height="200"> -->
    <main>
        <div id="leftContainer">
            <h1></h1>
            <div id="res"></div>
        </div>

        <div id="rightContainer">
            <!-- 
                <label for="id">car name</label>
                <input type="text" id="id">
                <br>
            -->
            <div class="container">
                <div class="buttonContainer">
                    <!-- <button id="search" type="button">Search</button> -->

                </div>

            </div>

        </div>
    </main>

    <script>
        $(document).ready(function() {

            var tableName = <?php echo json_encode($table, JSON_HEX_TAG); ?>;
            $("h1").append(titleCase(tableName) + " Table");
            $.ajax({
                url: "php/homeService.php",
                type: "POST",
                success: function(data) {
                    var tables = JSON.parse(data);
                    while (tables.length > 0) {
                        var tbl = titleCase(tables.pop());
                        if (tbl == "Login ") {
                            continue;
                        }
                        $("#tabList").append(buildTab(tbl, "table.php?table=" + tbl));
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });

            $("h1").html(titleCase(tableName) + " Table");

            $("#search").click(function() {



                $.ajax({
                    url: "php/searchService.php",
                    type: "POST",
                    data: {
                        table: tableName,
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        const twoDArray = data.map(Object.values);

                        var table = $("<table>");

                        var row = $("<tr>");

                        for (let j = 0; j < twoDArray[0].length; j++) {
                            row.append("<th>" + twoDArray[0][j] + "</th>");
                        }
                        table.append(row);

                        for (let i = 1; i < twoDArray.length; i++) {
                            row = $("<tr>");

                            for (let j = 0; j < twoDArray[i].length; j++) {
                                row.append("<th>" + twoDArray[i][j] + "</th>");
                            }
                            table.append(row);
                        }

                        $("#res").append(table);
                    }
                });
            });



        });

        function buildTab(title, link) {
            var tab = "<li><a href='" + link + "'>" + title + "</a></li>";
            return tab;
        }

        function titleCase(st) {
            return st.toLowerCase().split(" ").reduce((s, c) =>
                s + "" + (c.charAt(0).toUpperCase() + c.slice(1) + " "), '');
        }
    </script>

</body>

</html>