<!-- Home Page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brian Christner and Jimmy Connors">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <!-- Login form -->
    <div class="row justify-content-center col-12" id="loginform">
        <div class="col-5" style="min-width:500px;">
            <div class="box-section">
                <h1 style="margin-left:0px;" class="text-center">Sign In:</h1>
                <form action="?command=login" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <span class="error" id="emailerror"> <?php echo $emailError; ?></span>
                        <input onblur="validateEmail()" onkeyup="validateEmail()" type="email" class="form-control" id="email" name="email" required />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <span class="error" id="passworderror"> <?php echo $passwordError; ?></span>
                        <input onblur="validatePassword()" onkeyup="validatePassword()" type="password" class="form-control" id="password" name="password" required />
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="submit" disabled>Sign In</button>
                    </div>
                    <a class="btn btn-sm" href="?command=signup">Create a new account</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Form validation functions -->
    <script>
        // Save the last result of the validations
        let email_is_valid = false;
        let password_is_valid = false;

        function validateForm() {
            // Get the submit button
            let button = $("#submit");
            if (email_is_valid && password_is_valid) {
                // Form is valid
                // Enable the button
                button.prop("disabled", false);
            } else {
                // Form is not valid
                // Disable the button
                button.prop("disabled", true);
            }
        }

        function validateEmail() {
            // Get email input
            let emailInput = $("#email");

            // Get email error box
            let emailError = $("#emailerror");

            // Check if email is empty
            if (emailInput.val().length === 0) {
                emailError.text("* Please enter an email");
                emailInput.addClass("is-invalid");
                email_is_valid = false;

            } else if (/^(([a-zA-Z0-9\+\-_])+(\.(([a-zA-Z0-9\+\-_])+))*)@(([A-Za-z0-9\-])+(\.(([A-Za-z0-9\-])+))+)$/.test(emailInput.val()) === false) {
                // Email is invalid
                emailError.text("* Please enter a valid email");
                emailInput.addClass("is-invalid");
                email_is_valid = false;

            } else {
                // Email is valid
                emailError.text("");
                emailInput.removeClass("is-invalid");
                email_is_valid = true;
            }

            // Validate the form
            validateForm();
        }

        function validatePassword() {
            // Get password input
            let passwordInput = $("#password");

            // Get password error box
            let passwordError = $("#passworderror");

            // Check if password is empty
            if (passwordInput.val().length === 0) {
                passwordError.text("* Please enter a password");
                passwordInput.addClass("is-invalid");
                password_is_valid = false;

            } else {
                // Password is valid
                passwordError.text("");
                passwordInput.removeClass("is-invalid");
                password_is_valid = true;
            }

            // Validate the form
            validateForm();
        }
    </script>
</body>

</html>