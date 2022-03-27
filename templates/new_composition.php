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
</head>

<body>
    <!-- Navbar -->
    <?php Builder::navbar(); ?>

    <div class="box-section">
        <div class="row">
            <h2 class="box-title">Create Composition</h2>
        </div>
        <div class="row col-6">
            <form action="?command=new_composition" method="post" enctype='multipart/form-data'>
                <div class="mb-3">
                    <label for="composition-name" class="form-label">Composition Name</label>
                    <input class="form-control" id="composition-name" name="composition-name" placeholder="Composition Name" required>
                </div>
                <div class="mb-3">
                    <label for="backtrack" class="form-label">Backtrack</label>
                    <input class="form-control" name="backtrack" id="backtrack" type="file" placeholder="Backtrack" required accept="audio/*">
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>