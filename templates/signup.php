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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <!-- Signup form -->
    <div class="row justify-content-center col-12" id="loginform">
        <div class="col-5" style="min-width: 500px;">
            <div class="box-section">
                <h1 style="margin-left:0px;" class="text-center">Sign Up:</h1>
                <form action="?command=signup" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <span class="error" id="nameerror"> <?php echo $nameError; ?></span>
                        <input onblur="validateName()" onkeyup="validateName()" type="text" class="form-control" id="name" name="name" required />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <span class="error" id="emailerror"> <?php echo $emailError; ?></span>
                        <input onblur="validateEmail()" onkeyup="validateEmail()" type="email" class="form-control" id="email" name="email" required />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <span class="error" id="passworderror"> <?php echo $passwordError; ?></span>
                        <input onblur="validatePassword(); validatePasswordValid()" onkeyup="validatePassword(); validatePasswordValid()" type="password" class="form-control" id="password" name="password" required />
                    </div>

                    <div class="mb-3">
                        <label for="passwordvalid" class="form-label">Enter Password Again </label>
                        <span class="error" id="passwordvaliderror"></span>
                        <input onblur="validatePasswordValid()" onkeyup="validatePasswordValid()" type="password" class="form-control" id="passwordvalid" name="passwordvalid" required />
                    </div>

                    <div class="btn-group">
                        <button id="submit" type="submit" class="btn btn-primary" disabled>Sign Up</button>
                    </div>
                    <a class="btn btn-sm" href="?command=login">return to Sign In page</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>


    <!-- Form validation functions -->
    <script>
        // Save the last result of the validations
        let name_is_valid = false;
        let email_is_valid = false;
        let password_is_valid = false;
        let password_valid_is_valid = false;

        function validateForm() {
            // Get the submit button
            let button = $("#submit");
            if (name_is_valid && email_is_valid && password_is_valid && password_valid_is_valid) {
                // Form is valid
                // Enable the button
                button.prop("disabled", false);
            } else {
                // Form is not valid
                // Disable the button
                button.prop("disabled", true);
            }
        }

        function validateName() {
            // Get name input
            let nameInput = $("#name");

            // Get name error box
            let nameError = $("#nameerror");

            // Check if name is empty
            if (nameInput.val().length === 0) {
                nameError.text("* Please enter a name");
                nameInput.addClass("is-invalid");
                name_is_valid = false;

            } else {
                // name is valid
                nameError.text("");
                nameInput.removeClass("is-invalid");
                name_is_valid = true;

            }

            // Validate the form
            validateForm();
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

        function validatePasswordValid() {
            // Get password input
            let passwordInput = $("#passwordvalid");

            // Get real password
            let passwordReal = $("#password");

            // Get password error box
            let passwordError = $("#passwordvaliderror");

            // Check if password valid matches the password
            if (passwordInput.val() !== passwordReal.val()) {
                passwordError.text("* Your passwords do not match");
                passwordInput.addClass("is-invalid");
                password_valid_is_valid = false;

            } else {
                // Password is valid
                passwordError.text("");
                passwordInput.removeClass("is-invalid");
                password_valid_is_valid = true;
            }

            // Validate the form
            validateForm();
        }
    </script>
</body>

</html>