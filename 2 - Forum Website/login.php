<?php session_start();

?>

    <div class="content">
        <div class="enter">

            <!-- ////    FORM FOR LOGIN    //// -->
            <h5> Please login here: </h5>
            <form name="loginform" method="POST" >
                <label for="email_input">Email:</label><input type="text" name="email" id="email_input"> <br>
                <label for="password_input">Password:</label><input type="password" name="password" id="password_input"> <br> <br>
                <input type="submit" name="login1" value="Login"> <br><br>
            </form>



            <!-- HANDLES LOGIN ATTEMPTS  -->
            <?php
            // Once User attempts a login
            if (isset($_POST['login1'])) {

                /// Has the user entered a password and email?
                if (isset($_POST['email']) && $_POST['password']) {
                    include 'database.php';

                    // Password and email entered; prepare query
                    $loginattempt = $mysqli->prepare('select COUNT(*), user_id, hashed_password FROM users WHERE email=?');

                    // Bind the inputted email
                    $emailinput = (string) $_POST['email'];
                    $loginattempt->bind_param('s', $emailinput);
                    $loginattempt->execute();

                    // Bind the query results
                    $loginattempt->bind_result($cnt, $user_id, $pwd_hash);
                    $loginattempt->fetch();

                    // Retrieve input password
                    $pwd_guess = (string) $_POST['password'];

                    // Compare the submitted password to the actual password hash
                    if ($cnt == 1 && password_verify($pwd_guess, $pwd_hash)) {
                        // Login succeeded; update user_id & token session and redirect to home
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
			//header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php');
			header('Location: /portfolio/forum/index.php');
                    } else {
                        // Login failed; redirect back to the login screen
                        echo '<h4> Login failed! Please check your password and email! </h4> <br>';
                    }
                } else {

                    /// Password or email not entered
                    echo '<h4> Please enter an email and password. </h4> <br>';
                }
            }

            ?>


            <h5> or: </h5>


            <!-- ////    FORM FOR SIGNUP BUTTON    //// -->
            <h5> Signup here:</h5>
            <form name="signupbutton" method="POST" >
                <label for="email_input_signup">Email:</label><input type="text" name="emailsignup" id="email_input_signup"> <br>
                <label for="password_input_signup">Password:</label><input type="password" name="passwordsignup" id="password_input_signup"> <br>
                <label for="password_input_signup_confirm">Confirm Password:</label><input type="password" name="passwordsignupconfirm" id="password_input_signup_confirm"> <br>
                <label for="firstname_input">First name:</label><input type="text" name="firstname" id="firstname_input"> <br>
                <label for="lastname_signup">Last name:</label><input type="text" name="lastname" id="lastname_signup"> <br>
                <input type="submit" name="signupstart" value="Signup"> <br><br>
            </form>
        </div>


        <!-- HANDLES SIGNUP ATTEMPTS -->
        <?php
        /// Once the user clicks signup
        if (isset($_POST['signupstart'])) {

            /// Has the user entered an email, password, first name, and last name?
            if (isset($_POST['emailsignup']) && isset($_POST['passwordsignup']) && isset($_POST['passwordsignupconfirm']) && isset($_POST['firstname']) && isset($_POST['lastname'])) {
                
                /// Check if the passwords do not match 
                if ($_POST['passwordsignup'] != $_POST['passwordsignupconfirm']) {
                    
                    // Passwords do not match! Alert the user. 
                    echo '<br> <h6> Passwords do not match. </h6> <br>';
                } else {
                    require 'database.php';

                    /// Prepare to search database for inputted email
                    $retrieveemail = $mysqli->prepare("select user_id from users where email = '" . (string) $_POST['emailsignup'] . "'");
                    if (!$retrieveemail) {
                        printf("Query Prep Faileed: %s\n", $mysqli->error);
                        exit;
                    } else {

                        /// If select command is valid, execute it and bind its results 
                        $retrieveemail->execute();
                        $retrieveemail->bind_result($id);
                        $retrieveemail->fetch();

                        /// Check if any matches to the email were found
                        if (isset($id)) {

                            /// Email is in use! Alert user.
                            echo '<h6> This email is in use. </h6>';
                        } else {

                            /// Email is not in use! Retrieve entered name, email, and password
                            $firstname = (string) $_POST['firstname'];
                            $lastname = (string) $_POST['lastname'];
                            $email = (string) $_POST['emailsignup'];
                            $password = password_hash((string)$_POST['passwordsignup'], PASSWORD_DEFAULT);

                            /// Prepare insertion
                            $adduser = $mysqli->prepare('insert into users (email, first_name, last_name, hashed_password) values (?, ?, ?, ?)');
                            if (!$adduser) {
                                printf("Query Prep Failedd: %s\n", $mysqli->error);
                                exit;
                            }

                            /// Bind email, name, and password, execute, and close. 
                            $adduser->bind_param('ssss', $email, $firstname, $lastname, $password);
                            $adduser->execute();
                            $adduser->close();

                            /// Let user know they can now login.
                            echo '<h4> Sign up successful! Please login above! </h4>';
                        }
                    }
                }
            } else {

                /// A required field has been left blank! Alert the user. 
                echo '<h4>Please enter an email and password. </h4><br>';
            }
        }
        ?>

    </div>
