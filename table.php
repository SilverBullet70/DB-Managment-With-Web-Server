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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="css/tableStyle.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    <script src="./js/tableBuilding.js"></script>
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
                    <li><a id="selectBtn">Search</a></li>
                    <li><a id="insertBtn">Insert</a></li>
                    <li><a id="updateBtn">Update</a></li>

                </ul>
            </div>

            <form id="selectForm">
                <div id="searchFields" class="queryFormContainer">



                </div>

                <div class="container">
                    <div class="buttonContainer">
                        <button id="search" type="submit">Search</button>

                    </div>

                </div>
            </form>

            <form id="insertForm">
                <div id="insertFields" class="queryFormContainer">



                </div>

                <div class="container">
                    <div class="buttonContainer">
                        <button id="insert" type="submit">Insert</button>

                    </div>

                </div>
            </form>

            <form id="updateForm">
                <div id="updateFields" class="idFormContainer">



                </div>

                <div class="container">
                    <div class="buttonContainer">
                        <button id="update" type="submit">Update</button>

                    </div>

                </div>
            </form>

        </div>
    </main>

    
    </script>

</body>

</html>