<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Jimmy Connors and Brian Christner">
    <title>Create Composition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <?php Builder::navbar(); ?>

    <div class="row justify-content-center col-12">
        <div class="col-6" style="min-width: 500px">
            <!-- Box to hold composition form -->
            <div class="box-section">
                <div class="row">
                    <h2 class="box-title text-center">Create Composition</h2>
                </div>
                <form action="?command=new_composition" method="post" enctype='multipart/form-data'>
                    <div class="mb-3">
                        <label for="composition-name" class="form-label">Composition Name</label>
                        <span class="error" id="composition-nameerror"> <?php echo $compositionError; ?></span>
                        <input onblur="validate()" onkeyup="validate()" class="form-control" id="composition-name" name="composition-name" placeholder="Composition Name" required>
                    </div>
                    <div class="mb-3">
                        <!-- Required backtrack for composition -->
                        <!-- May soon become optional along with a required duration for no backtrack -->
                        <label for="backtrack" class="form-label">Backtrack</label>
                        <input class="form-control" name="backtrack" id="backtrack" type="file" required accept="audio/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Validation of composition-name -->
    <script>
        function validate() {
            // Get composition name box
            let compName = $("#composition-name");

            // Get composition name error
            let compError = $("#composition-nameerror");

            // Check if empty
            if (compName.val().length === 0) {
                compError.text("* Enter a composition name");
                compName.addClass("is-invalid");
            } else if (compName.val().includes("/")) {
                // Cannot contain /
                compError.text("* Illegal character \"/\"");
                compName.addClass("is-invalid");
            } else if (compName.val().includes("'")) {
                // Cannot contain '
                compError.text("* Illegal character \"'\"");
                compName.addClass("is-invalid");
            } else if (compName.val().includes("\"")) {
                // Cannot contain "
                compError.text("* Illegal character \"");
                compName.addClass("is-invalid");
            } else {
                // Composition name is valid
                compError.text("");
                compName.removeClass("is-invalid");
            }
        }
    </script>
</body>

</html>