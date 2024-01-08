$(document).ready(function () {

    var tableName = new URLSearchParams(window.location.search).get("table");
    buildQueryForm(tableName);
    buildIdQueryForm(tableName);

    $("#insertForm").hide();
    $("#updateForm").hide();

    $("#selectBtn").click(function () {
        $("#selectForm").show();
        $("#insertForm").hide();
        $("#updateForm").hide();
    });

    $("#insertBtn").click(function () {
        $("#selectForm").hide();
        $("#insertForm").show();
        $("#updateForm").hide();
    });

    $("#updateBtn").click(function () {
        $("#selectForm").hide();
        $("#insertForm").hide();
        $("#updateForm").show();
    });


    $("h1").append(titleCase(tableName) + " Table");
    //$("h1").html(titleCase(tableName) + " Table");

    $.ajax({
        url: "./php/homeService.php",
        type: "POST",
        async: false,
        success: function (data) {
            var tables = JSON.parse(data);
            while (tables.length > 0) {
                var tbl = titleCase(tables.pop());
                if (tbl == "Login ") {
                    continue;
                }
                $("#tabList").append(buildTab(tbl, "table.php?table=" + tbl));
            }
        },
        error: function (data) {
            console.log(data);
        }
    });



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


    $("#selectForm").submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // 'this' refers to the form

        var whereClause = "";
        for (var pair of formData.entries()) {
            if (pair[1] != "" && pair[1] != null && pair[1] != undefined)
                whereClause += pair[0] + '= "' + pair[1] + '" AND ';
        }
        if (whereClause != "") {
            let regex = /AND $/;
            whereClause = whereClause.replace(regex, ";");
        } else
            whereClause = "null";

        $("#res").html(whereClause);
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

            $("#res").html(table);
        }, tableName, "*", whereClause);
    });


    $("#insertForm").submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // 'this' refers to the form

        var values = "";
        for (var pair of formData.entries()) {
            if (pair[1] != "" && pair[1] != null && pair[1] != undefined)
                values += '"' + pair[1] + '", ';
        }
        if (values != "") {
            let regex = /, $/;
            values = values.replace(regex, "");
        } else
            values = "null";

        $("#res").html(values);
        insert(tableName, values);
    });

    $("#updateForm").submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // 'this' refers to the form

        var values = "";
        var condition = "";
        var first = true;
        for (let entry of formData.entries()) {
            let key = entry[0];
            let value = entry[1];

            if (first) {
                condition += key + '= "' + value + '"; ';
                first = false;
            } else {
                if (value != "" && value != null && value != undefined)
                    values += key + '= "' + value + '", ';
            }
        }
        if (values != "") {
            let regex = /, $/;
            values = values.replace(regex, "");
        } else
            values = "null";

        console.log(values);

        // if (condition != "") {
        //     let regex = /AND $/;
        //     condition = condition.replace(regex, "");
        // } else
        //     condition = "null";

        $("#res").html(values);
        update(tableName, values, condition);
    });






});

function search(func, tableName, column, condition) {
    $.ajax({
        url: "./php/searchService.php",
        type: "POST",
        async: false,
        data: {
            table: tableName,
            column: column,
            condition: condition,
        },
        success: function (data) {
            func(data, tableName);
        }
    });
}


function insert(tableName, values) {
    $.ajax({
        url: "./php/insertService.php",
        type: "POST",
        async: false,
        data: {
            table: tableName,
            values: values,
        },
        success: function () {
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

                $("#res").html(table);
            }, tableName, "*", "null");
        }
    });
}

function update(tableName, values, condition) {
    $("#res").html(values);
    $.ajax({
        url: "./php/updateService.php",
        type: "POST",
        async: false,
        data: {
            table: tableName,
            values: values,
            condition: condition,
        },
        success: function () {
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

                $("#res").html(table);
            }, tableName, "*", "null");
        }
    });
}


function isFK(column, tableName, callback) {

    foreignKeys(tableName, function (twoDArray) {
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
        url: "./php/getForeignKeysService.php",
        type: "POST",
        async: false,
        data: {
            table: tableName
        },
        success: function (data) {
            var parsedData = JSON.parse(data);
            const twoDArray = parsedData.map(Object.values);
            if (typeof callback === "function") {
                callback(twoDArray);
            }
        }
    });
}

function getFKOptions(column, tableName) {
    return new Promise((resolve, reject) => {
        foreignKeys(tableName, function (twoDArray) {
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
        url: "./php/getColumnsAndTypes.php",
        type: "POST",
        async: false,
        data: {
            table: tableName
        },
        success: function (data) {
            var parsedData = JSON.parse(data);
            const twoDArray = parsedData.map(Object.values);
            if (typeof callback === "function") {
                callback(twoDArray);
            }
        }
    });
}



async function buildQueryForm(tableName) {
    getColumnsAndTypes(tableName, async (cols) => {
        var form = $(".queryFormContainer");
        for (let i = 0; i < cols.length; i++) {
            var col = await cols[i][0];
            var type = await cols[i][1];
            var length = await cols[i][2];

            await isFK(col, tableName, async (found) => {
                if (found) {
                    var select = await getFKDropdown(col, tableName);
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

async function buildIdQueryForm(tableName) {
    getColumnsAndTypes(tableName, async (cols) => {
        var form = $(".idFormContainer");

        var col = await cols[0][0];
        var type = await cols[0][1];
        var length = await cols[0][2];
        form.append($("<label>").text("Update Row With " + titleCase(col)));
        var idSelect = await getPKDropdown(col, tableName);
        form.append(idSelect);


        for (let i = 1; i < cols.length; i++) {
            var col = await cols[i][0];
            var type = await cols[i][1];
            var length = await cols[i][2];

            await isFK(col, tableName, async (found) => {
                if (found) {
                    var select = await getFKDropdown(col, tableName);
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



// async function getFKDropdown(column, tableName) {
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

async function getFKDropdown(column, tableName) {
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

async function getPKDropdown(column, tableName) {
    var select = $("<select>").attr("name", column);
    select.addClass("autoFill");
    try {
        search((data) => {
            var data = JSON.parse(data);
            const arr = data.map(Object.values);
            for (let i = 0; i < arr.length; i++) {
                select.append($("<option>").attr("value", arr[i]).text(arr[i]));
            }
        }, tableName, column, "null");
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