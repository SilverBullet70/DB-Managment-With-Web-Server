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
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 -->

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
            buildQueryForm(tableName);
            //foreignKeys("car");
            // $("#searchForm").append(getForeignKeys(tableName));

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
                async: false,
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
                async: false,
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

        function isFK(column, tableName, callback) {

            foreignKeys(tableName, function(twoDArray) {
                let found = false;
                for (let i = 0; i < twoDArray.length; i++) {
                    if (twoDArray[i][1].trim() === column.trim()) {
                        found = true;
                        break;
                    }
                }
                if (typeof callback === "function") {
                    callback(found);
                }
            });

        }

        function foreignKeys(tableName, callback) {
            $.ajax({
                url: "php/getForeignKeysService.php",
                type: "POST",
                async: false,
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

        // function getFKOptions(column, tableName) {
        //     foreignKeys(tableName, function(twoDArray) {
        //         for (let i = 0; i < twoDArray.length; i++) {
        //             if (twoDArray[i][1] == column) {
        //                 var fkTable = twoDArray[i][2];
        //                 var fkColumn = twoDArray[i][3];
        //                 search((data) => {
        //                     var data = JSON.parse(data);
        //                     const arr = data.map(Object.values);
        //                     return arr;

        //                 }, fkTable, fkColumn, "null");
        //             }
        //         }
        //     });
        // }


        function getFKOptions(column, tableName) {
            return new Promise((resolve, reject) => {
                foreignKeys(tableName, function(twoDArray) {
                    for (let i = 0; i < twoDArray.length; i++) {
                        if (twoDArray[i][1] == column) {
                            var fkTable = twoDArray[i][2];
                            var fkColumn = twoDArray[i][3];
                            search((data) => {
                                var data = JSON.parse(data);
                                const arr = data.map(Object.values);
                                resolve(arr); // Resolve the promise with the data
                            }, fkTable, fkColumn, "null");
                        }
                    }
                });
            });
        }


        function getColumnsAndTypes(tableName, callback) {
            $.ajax({
                url: "php/getColumnsAndTypes.php",
                type: "POST",
                async: false,
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

        // async function buildQueryForm(tableName) {
        //     getColumnsAndTypes(tableName, (cols) => {

        //         var form = $("#searchForm");
        //         for (let i = 0; i < cols.length; i++) {
        //             var col = cols[i][0];
        //             var type = cols[i][1];
        //             var length = cols[i][2];

        //             isFK(col, tableName, (found) => {
        //                 if (found) {
        //                     form.append($("<label>").text(titleCase(col)));

        //                     var select = getDropdown(col, tableName);
        //                     $("#res").append(select);
        //                     form.append(select);
        //                 } else {
        //                     form.append($("<label>").text(titleCase(col)));
        //                     form.append($("<input>").attr("type", "text").attr("name", col).attr("placeholder", titleCase(col)));
        //                 }
        //             });
        //         }
        //     });

        // }


        async function buildQueryForm(tableName) {
            getColumnsAndTypes(tableName, async (cols) => {
                var form = $("#searchForm");
                for (let i = 0; i < cols.length; i++) {
                    var col = await cols[i][0];
                    var type = await cols[i][1];
                    var length = await cols[i][2];

                    await isFK(col, tableName, async (found) => {
                        if (found) {
                            var select = await getDropdown(col, tableName);
                            form.append($("<label>").text(titleCase(col)));
                            form.append(select);
                        } else {
                            form.append($("<label>").text(titleCase(col)));
                            form.append($("<input>").attr("type", "text").attr("name", col).attr("placeholder", titleCase(col)));
                        }
                    });
                }
            });
        }



        // async function getDropdown(column, tableName) {
        //     var select = $("<select>").attr("name", column);
        //     select.addClass("autoFill");
        //     var options = await getFKOptions(column, tableName);

        //     $("#res").append(JSON.stringify(options));

        //     $("#res").append("  " + options.length);
        //     for (let i = 0; i < options.length; i++) {
        //         $("#res").append("  " + i);

        //         select.append($("<option>").attr("value", options[i]).text(options[i]));
        //     }
        //     return select;
        // }

        async function getDropdown(column, tableName) {
            var select = $("<select>").attr("name", column);
            select.addClass("autoFill");
            try {
                var options = await getFKOptions(column, tableName);
                for (let i = 0; i < options.length; i++) {
                    select.append($("<option>").attr("value", options[i]).text(options[i]));
                }
            } catch (error) {
                console.error("Error getting dropdown options:", error);
            }

            return select;
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