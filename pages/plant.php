<?php
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
    $hardiness=$result[0]['hardiness_level'];

    $tags=exec_sql_query(
        $db,
        "SELECT
        plants.id AS 'plants.id',
        tags.name AS 'tags.name'
        FROM plants INNER JOIN entry_tags ON (plants.id = entry_tags.plant_id) INNER JOIN tags ON (entry_tags.tag_id = tags.id)
        WHERE (plants.id = :plant_id);",
        array(
            ':plant_id' => $plant_id
        )
    )->fetchAll();


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
        <?php if(is_user_logged_in()){?>
            <div class="align-right">
                <ul class="nav_bar">
                <li><a href="/">Consumer View</a></li>
                <li><a href="/admin">Admin View</a></li>
                <li><a href="<?php echo logout_url();?>">Logout</a></li>
                </ul>
            </div>
            <?php } else{?>
                <div class="rows_titlenav">
                    <div class="returnToCat" >
                        <a href="/"> Consumer Catalog </a>
                    </div>
                        <a class="login_alone" href="/login"> Log-in </a>
                </div>

        <?php }?>
        <h3><?php echo htmlspecialchars($name)?></h3>
        <h4 class="sciname"><?php echo htmlspecialchars($sci_name)?></h4>
        <?php
            $result_documentstable = exec_sql_query(
            $db,
            "SELECT file_name AS 'documents.file_name', file_ext AS 'documents.file_ext' FROM documents WHERE (id=:plant_id);",
            array(
            ':plant_id' => $plant_id
            )
        )->fetchAll();
        ?>
        <img src = "/public/uploads/documents/<?php echo htmlspecialchars($result_documentstable[0]['documents.file_name']);?>.<?php echo htmlspecialchars($result_documentstable[0]['documents.file_ext']);?>" alt="Image of <?php echo htmlspecialchars($name);?>"/>
        <p>Hardiness Level: <?php if($hardiness!=''){echo htmlspecialchars($hardiness);}else{echo "unknown";}?></p>
        <h5>Tags</h5>
        <?php
         foreach($tags as $tag){
            echo "<p class='tag'>";
            echo htmlspecialchars($tag['tags.name']);
            echo "</p>";
         }
        ?>
    </main>

</body>
</html>
