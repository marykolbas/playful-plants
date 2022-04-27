<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all" />
  <title>Playful Plants Project</title>
</head>

<body>

    <main class="center">
      <h1>Playful Plants Project</h1>
      <?php if(!is_user_logged_in()){ ?>
        <p> If you are an administrator, log-in to edit database. Otherwise, <a href="/"> return to the catalog.</a></p>
        <?php
          echo_login_form('/login', $session_messages);
        ?>
      <?php } else {?>
        <p> Hello <?//php echo htmlspecialchars(current_user());?>! You have been logged in. <a href="/admin"> Access the admin catalog.</a></p>
      <?php } ?>
    </main>

</body>
</html>
