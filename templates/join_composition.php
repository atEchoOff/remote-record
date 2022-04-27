<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jimmy Connors and Brian Christner">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <!-- Navbar -->
    <?php Builder::navbar(); ?>

    <!-- Your projects -->
    <h1>Welcome, <?php echo $_SESSION["name"]; ?>!</h1>
    <div class="box-section">
        <div class="row justify-content-end">
            <div>
                <h2 class="box-title mr-auto ml-auto">Join A Project</h2>
                <a id="reload" onclick="reloadTable()" class="btn btn-dark ml-auto mr-1" style="width: 100px; height: 40px; float:right;">Reload</a>
            </div>
        </div>

        <table id="tableOfComps">
            <tr>
                <!-- Table heading -->
                <th style="width:5%;">Composition Name</th>
                <th style="width:2.5%;">Your Roles</th>
                <th style="width:2.5%;">Composer</th>
            </tr>
            <?php
            // For each composition, print whether or not user is the composer, the name, and the composer
            if (sizeof($compositions) === 0) {
                echo "
                <tr>
                    <td>No other compositions exist</td>
                    <td></td>
                    <td></td>
                </tr>";
            }

            foreach ($compositions as $composition) {
                echo "
              <tr>
                <td><a href='?command=join_composition&composition={$composition['name']}'>{$composition['name']}</a></td>
                <td><strong>-</strong></td>
                <td>{$composition['composer_name']}</td>
              </tr>
              ";
            }
            ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function TableObject(jsonObj) {
            this.compositionName = jsonObj["name"];
            this.composerName = jsonObj["composer_name"];
            this.generateRow = function() {
                var row = $('<tr>');
                var link = $("<td><a href='?command=join_composition&composition=" + this.compositionName + "'>" + this.compositionName + "</a></td>");
                row.append(link);
                var thing2 = $("<td><strong>-</strong></td>");
                row.append(thing2);
                var thing3 = $("<td>" + this.composerName + "</td>");
                row.append(thing3);
                console.log("hithere")
                return row;
            }
        }

        var reloadTable = () => {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: {
                    "command": "get_new_composition_json"
                },
                success: function(response) {
                    var tableElements = Array();
                    for (let i = 0; i < response.length; i++) {
                        tableElements.push(new TableObject(response[i]));
                    }
                    var tab = $("#tableOfComps");
                    tab.empty();
                    tab.append('<tr><th style="width:5%;">Composition Name</th><th style="width:2.5%;">Your Roles</th><th style="width:2.5%;">Composer</th></tr>');
                    tableElements.forEach(element => tab.append(element.generateRow()));
                    // If there are no new table elements, show dialogue
                    if (tableElements.length === 0) {
                        tab.append("<tr><td>No other compositions exist</td><td></td><td></td></tr>")
                    }
                }
            })
        }
    </script>
</body>

</html>