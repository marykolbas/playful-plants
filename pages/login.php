<?php
    $username_feedback='hidden';
    $password_feedback='hidden';
    $matching_feedback='hidden';

    $sticky_username='';

    if (isset($_POST['login_submit'])) {

        $username = trim($_POST['username']); // untrusted
        $password = trim($_POST['password']); // untrusted

        $form_valid = True;

        if (empty($username)) {
          $form_valid = False;
          $username_feedback = '';
        }
        if (empty($password)) {
          $form_valid = False;
          $password_feedback = '';
        }
        if(False){ //if they don't match the records in the database
          $form_valid = False;
          $matching_feedback = '';
        }
        }

        if($form_valid){
          //not done yet becasue it must check if it was correctly sent to db
          //change to administrator mode (not sure how to yet in Milestone 1)
        }
        else{
          // set sticky values
          $sticky_username=$username;
          //don't sticky the password
        }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all" />
  <title>Playful Plants Project</title>
</head>

<body>

    <main>
      <h1>Playful Plants Project</h1>
        <p> If you are an administrator, log-in to edit database. Otherwise, <a href="/"> return to the catalog.</a></p>
        <form method="post" action="/login" novalidate>
        <div class="feedback <?php echo $matching_feedback; ?>">
            The username and password entered does not match with our records. Please try again.
        </div>
        <div class="feedback <?php echo $username_feedback; ?>">
            Please enter your username.
        </div>
        <div class="form_element">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($sticky_username)?>"/>
        </div>
        <div class="feedback <?php echo $password_feedback; ?>">
            Please enter your password.
        </div>
        <div class="form_element">
            <label for="password">Password:</label>
            <input type="text" id="password" name="password"/>
        </div>
        <div class="align-right">
            <input type="submit" value="Log-in" name="login_submit"/>
        </div>
        </form>
    </main>

</body>
</html>
