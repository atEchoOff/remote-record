<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brian Christner and Jimmy Connors">
    <title>Sign up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <!-- Signup form -->
    <div class="row justify-content-center">
        <div class="col-4">
            <h1 class="text-center">Sign Up:</h1>
            <form action="?command=signup" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <span class="error"> <?php echo $nameError; ?></span>
                    <input type="text" class="form-control" id="name" name="name" required />
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <span class="error"> <?php echo $emailError; ?></span>
                    <input type="email" class="form-control" id="email" name="email" requred />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <span class="error"> <?php echo $passwordError; ?></span>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </div>
                <a class="btn btn-sm" href="?command=login">return to Sign In page</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>