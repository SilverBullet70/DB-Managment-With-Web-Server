<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: registration.html");
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Cars DB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "php/homeService.php",
                type: "POST",
                success: function(data) {
                    var cols = JSON.parse(data);
                    while (cols.length > 0) {
                        var col = titleCase(cols.pop());
                        if (col == "Login ") {
                            continue;
                        }
                        $("#res").append(buildCard(col, "table.php?table=" + col));
                    }
                }
            });



            function titleCase(st) {
                return st.toLowerCase().split(" ").reduce((s, c) =>
                    s + "" + (c.charAt(0).toUpperCase() + c.slice(1) + " "), '');
            }

            function buildCard(title, link) {
                var card = "<div class='ag-courses_item'><a href='" + link + "' class='ag-courses-item_link'><div class='ag-courses-item_bg'></div><div class='ag-courses-item_title'>" + title + "</div></a></div>";
                return card;
            }
        });
    </script>

    <div class="title">
        <h1>Database tables whith PHP</h1>

    </div>

    <div class="ag-format-container">
        <div id="res" class="ag-courses_box">




            <!-- 
            <div class="ag-courses_item">
                <a href="#" class="ag-courses-item_link">
                    <div class="ag-courses-item_bg"></div>

                    <div class="ag-courses-item_title">
                        Front-end development&#160;+ jQuery&#160;+ CMS
                    </div>
                </a>
            </div> -->




        </div>
    </div>


</body>

</html>