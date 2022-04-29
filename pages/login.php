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
        <div class="returnToCat" >
          <a href="/"> Return to Consumer Catalog </a>
        </div>
        <h2>Log-in</h2>
        <p> If you are an administrator, log-in to edit database</p>
        <?php
          echo_login_form('/login', $session_messages);
        ?>
      <?php } else {?>
        <div class="align-right">
        <ul class="nav_bar">
          <li><a href="/">Consumer View</a></li>
          <li><a href="/admin">Admin View</a></li>
          <li><a href="<?php echo logout_url();?>">Logout</a></li>
        </ul>
      </div>
        <p> Hello <?php echo htmlspecialchars($current_user['username']);?>! You have been logged in. Select a View above.</p>
      <?php } ?>
    </main>

</body>
</html>
