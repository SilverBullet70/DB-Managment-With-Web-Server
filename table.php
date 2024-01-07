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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>


<body>
    <div class="tabNavbar">
        <ul id="tabList">
            <li><a class="active" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>

        </ul>
    </div>


    <!-- <embed type="text/html" src="registration.html" width="500" height="200"> -->
    <main>
        <div id="leftContainer">
            <h1></h1>
            <div id="res" class="table-wrapper"></div>
        </div>

        <div id="rightContainer">

            <div class="queryNavbar">
                <ul id="querList">
                    <li><a class="active" href="">Search</a></li>
                    <li><a class="active" href="">Insert</a></li>
                    <li><a class="active" href="">Update</a></li>

                </ul>
            </div>

            <form>
                <div id="searchForm" class="queryFormContainer">



                </div>

                <div class="container">
                    <div class="buttonContainer">
                        <button id="search" type="button">Search</button>

                    </div>

                </div>
            </form>

        </div>
    </main>

    <script>
        $(document).ready(function() {

            var tableName = <?php echo json_encode($table, JSON_HEX_TAG); ?>;

            //foreignKeys("car");
            // $("#searchForm").append(getForeignKeys(tableName));

            getFKOptions("address", "customer");
            //  $("#searchForm").append(getFKOptions("address", tableName));
            // $("#searchForm").append(getForeignKeys(tableName));


            // $("form").submit(function(event) {
            //     const submitter = $("button[type=submit]");
            //     const formData = new FormData(form, submitter);


            //     $.ajax({
            //         type: "POST",
            //         url: "process.php",
            //         data: formData,
            //         dataType: "json",
            //         encode: true,
            //     }).done(function(data) {
            //         console.log(data);
            //     });

            //     event.preventDefault();
            // });

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

                search((data) => {
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
                }, tableName, "*", "null");





            });



        });

        function search(func, tableName, column, condition) {
            $.ajax({
                url: "php/searchService.php",
                type: "POST",
                data: {
                    table: tableName,
                    column: column,
                    condition: condition,
                },
                success: function(data) {
                    func(data, tableName);
                }
            });
        }

        // function foreignKeys(tableName) {
        //     $.ajax({
        //         url: "php/getForeignKeysService.php",
        //         type: "POST",
        //         data: {
        //             table: tableName
        //         },
        //         success: function(data) {
        //             // $("#res").append(data);
        //             var data = JSON.parse(data);

        //             const twoDArray = data.map(Object.values);
        //             return twoDArray;
        //         }
        //     });
        // }

        function isFK(column, tableName) {
            foreignKeys(tableName, function(twoDArray) {
                for (let i = 0; i < twoDArray.length; i++) {
                    if (twoDArray[i][0].trim() == column.trim()) {
                        return true;
                    }
                }
                return false;
            });


        }

        function foreignKeys(tableName, callback) {
            $.ajax({
                url: "php/getForeignKeysService.php",
                type: "POST",
                data: {
                    table: tableName
                },
                success: function(data) {
                    var parsedData = JSON.parse(data);
                    const twoDArray = parsedData.map(Object.values);
                    if (typeof callback === "function") {
                        callback(twoDArray);
                    }
                }
            });
        }

        function getFKOptions(column, tableName) {
            foreignKeys(tableName, function(twoDArray) {
                for (let i = 0; i < twoDArray.length; i++) {
                    if (twoDArray[i][1] == column) {
                        var fkTable = twoDArray[i][2];
                        var fkColumn = twoDArray[i][3];
                        search((data) => {
                            var data = JSON.parse(data);
                            const arr = data.map(Object.values);
                            return arr;

                        }, fkTable, fkColumn, "null");
                    }
                }
            });
        }

        function buildQueryForm() {

        }


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