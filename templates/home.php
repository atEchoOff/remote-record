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
  <nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Remote Record</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="?command=home">Home</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Your projects -->
  <div class="box-section">
    <div class="row">
      <h2 class="box-title">Your Projects</h2>
      <!-- Buttons to add an existing composition or make a new one -->
      <div class="btn-toolbar box-buttons" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="First group" style="margin-right:4px;">
          <button type="button" class="btn btn-dark">Join</button>
        </div>
        <div class="btn-group mr-2" role="group" aria-label="Second group">
          <a class="btn btn-primary" href="?command=new_composition">New</a>
        </div>
      </div>
    </div>
    <table>
      <tr>
        <!-- Table heading -->
        <th style="width:20%;">Composition Name</th>
        <th style="width:10%;">Your Roles</th>
        <th style="width:10%;">Last Edited</th>
      </tr>
      <tr>
        <!-- Compositions information (will be generated per user once we have a backend) -->
        <td><a href="?command=record">Verdi Requiem</a></td>
        <td>Composer</td>
        <td>30 minutes ago</td>
      </tr>
      <tr>
        <td><a href="?command=record">Let my Love be Heard</a></td>
        <td>Musician</td>
        <td>2 days ago</td>
      </tr>
      <tr>
        <td><a href="?command=record">Earth Song</a></td>
        <td>Composer, Musician</td>
        <td>17 days ago</td>
      </tr>
      <tr>
        <td><a href="?command=record">Overture to Candide</a></td>
        <td>Musician</td>
        <td>2 months ago</td>
      </tr>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>