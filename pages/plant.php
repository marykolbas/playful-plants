<?php
    $db = init_sqlite_db('db/site.sqlite', 'db/init.sql');
    $plant = $_GET['pp_id']; //from query string parameters, tainted???

    $result=exec_sql_query(
        $db,
        "SELECT * FROM plants WHERE (pp_id= :pp_id);",
        array(
            ':pp_id' => $plant
        )
    )->fetchAll();
    $name = $result[0]['name'];
    $sci_name = $result[0]['sci_name'];
    $plant_id=$result[0]['id'];

    //$tag_query = "SELECT * FROM tags WHERE (plant_id=" . $plant_id . ")";
    $tags_result = exec_sql_query(
        $db,
        "SELECT * FROM entrytags WHERE (plant_id= :plant_id);",
        array(

        )
    );

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

    <main class="center">
    <h1>Playful Plants Project</h1>
    <div class="align-right">
        <a href="/login"> Log-in </a>
    </div>
        <a href="/"> Return to Catalog </a>
        <h3><?php echo htmlspecialchars($name)?></h3>
        <h4 class="sciname"><?php echo htmlspecialchars($sci_name)?></h4>
        <img class="big_image" src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        <p> Classification: Other </p>
        <p>Growth Pattern: Annual</p>
        <p>Sun: Full Sun</p>
        <p>Hardiness: 4-9 </p>
        <?php
         foreach($tags_result as $tag){

         }
        ?>
    </main>

</body>
</html>
