<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Jimmy Connors and Brian Christner">
  <title>Home Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/main.css">
</head>

<body>
  <!-- Navbar -->
  <?php Builder::navbar(); ?>

  <!-- Your projects -->
  <h1 style="margin-left: 10px; margin: bottom 0px; margin-top:5px;">Welcome, <?php echo $_SESSION["name"]; ?>!</h1>
  <div class="box-section">
    <div class="row justify-content-end">
      <div>
        <h2 class="box-title">Your Projects</h2>
        <!-- Buttons to add an existing composition or make a new one -->
        <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
            <a class="btn btn-dark" href="?command=join_composition">Join</a>
          </div>
          <div class="btn-group mr-2" role="group" aria-label="Second group">
            <a class="btn btn-primary" href="?command=new_composition">New</a>
          </div>
        </div>
      </div>
    </div>
    <table>
      <tr>
        <!-- Table heading -->
        <th style="width:5%;">Composition Name</th>
        <th style="width:2.5%;">Your Roles</th>
        <th style="width:2.5%;">Composer</th>
      </tr>
      <?php
      // For each composition, print whether or not user is the composer, the name, and the composer
      foreach ($compositions as $composition) {
        if ($_SESSION["email"] === $composition["composer_email"]) {
          echo "
              <tr>
                <td><a href='?command=record&composition={$composition['name']}'>{$composition['name']}</a></td>
                <td>Composer, Musician</td>
                <td>{$composition['composer_name']}</td>
              </tr>
              ";
        } else {
          echo "
              <tr>
                <td><a href='?command=record&composition={$composition['name']}'>{$composition['name']}</a></td>
                <td>Musician</td>
                <td>{$composition['composer_name']}</td>
              </tr>
              ";
        }
      }
      ?>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>