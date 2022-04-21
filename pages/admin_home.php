<?php
  //open database
  $db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

  #starting variables
  $name = '';
  $sci_name = '';
  $plant_id = '';
  $exploratory_constructive = '';
  $exploratory_sensory = '';
  $physical = '';
  $imaginative = '';
  $restorative = '';
  $expressive = '';
  $play_with_rules = '';
  $bio = '';

  $name_feedback_class = 'hidden';
  $sci_name_feedback_class = 'hidden';
  $plant_id_feedback_class = 'hidden';
  $sci_name_feedback_unique = 'hidden';
  $plant_id_feedback_unique = 'hidden';


#FILTER FORM
  #starting variables for filter
  $exploratory_constructive_filter = '';
  $exploratory_sensory_filter = '';
  $physical_filter = '';
  $imaginative_filter = '';
  $restorative_filter = '';
  $expressive_filter = '';
  $play_with_rules_filter = '';
  $bio_filter = '';

  #sticky values for when form isnt submitted
  if(isset($_GET['apply_changes_submit'])){
    $filter_submitted = True;
  }
  if(!$filter_submitted){
    $sticky_sortby_name = '';
    $sticky_sortby_sci_name = '';
    $sticky_exploratory_constructive_filter = '';
    $sticky_exploratory_sensory_filter = '';
    $sticky_physical_filter = '';
    $sticky_imaginative_filter = '';
    $sticky_restorative_filter = '';
    $sticky_expressive_filter = '';
    $sticky_play_with_rules_filter = '';
    $sticky_bio_filter = '';
  }
  else{
    $sticky_sortby_name = ($_GET['sort']=='sortby_name' ? 'selected' : ''); #untrusted?
    $sticky_sortby_sci_name = ($_GET['sort']=='sortby_sci_name' ? 'selected' : '');
    $sticky_exploratory_constructive_filter = ($_GET['exploratory_constructive_box'] ? 'checked' : '');
    $sticky_exploratory_sensory_filter = ($_GET['exploratory_sensory_box'] ? 'checked' : '');
    $sticky_physical_filter = ($_GET['physical_box'] ? 'checked' : '');
    $sticky_imaginative_filter = ($_GET['imaginative_box'] ? 'checked' : '');
    $sticky_restorative_filter =($_GET['restorative_box'] ? 'checked' : '');
    $sticky_expressive_filter = ($_GET['expressive_box'] ? 'checked' : '');
    $sticky_play_with_rules_filter = ($_GET['play_with_rules_box'] ? 'checked' : '');
    $sticky_bio_filter = ($_GET['bio_box'] ? 'checked' : '');
  }

#DELETE FORM
$delete_feedback = False;
if(isset($_POST['delete_submit'])){
  $delete_submitted = True;
}
if($delete_submitted){
  $delete_plant_id = $_POST['plant_id']; #untrusted?

  //get pp_id before deleting for feedback message
  $deletequeryforppid = exec_sql_query($db, "SELECT plants.id AS 'plants.id', plants.pp_id AS 'plants.pp_id' FROM plants WHERE (plants.id=:id)", array(
    ':id' => $delete_plant_id
  ))->fetchAll();
  $deleted_pp_id = $deletequeryforppid[0]['plants.pp_id'];

  $delete_query = exec_sql_query($db, "DELETE FROM plants WHERE (id=:id)", array(
    ':id' => $delete_plant_id
  ));
  $delete_query_tags = exec_sql_query($db, "DELETE FROM entry_tags WHERE (plant_id=:plant_id)", array(
    ':plant_id' => $delete_plant_id
  ));
  $delete_query_documents = exec_sql_query($db, "DELETE FROM documents WHERE (id=:plant_id)", array(
    ':plant_id' => $delete_plant_id
  ));
  if($delete_query && $delete_query_tags && $delete_query_documents){ //correctly deleted
    $delete_feedback = True;
  }

}

  //query table
  $select_part = "SELECT * FROM plants ";
  $order_part = " ORDER BY ";
  $where_part = "";
  $order_part2 = "name;";
  //create list for SQL conditional expressions:
  $filter_exprs = array();
  if ($sticky_exploratory_constructive_filter=='checked'){
    //append SQl conditional expression to list:
    array_push($filter_exprs, "(exploratory_constructive = 1)");
  }
  if ($sticky_exploratory_sensory_filter=='checked'){
    array_push($filter_exprs, "(exploratory_sensory = 1)");
  }
  if ($sticky_physical_filter=='checked'){
    array_push($filter_exprs, "(physical = 1)");
  }
  if ($sticky_imaginative_filter=='checked'){
    array_push($filter_exprs, "(imaginative = 1)");
  }
  if ($sticky_restorative_filter=='checked'){
    array_push($filter_exprs, "(restorative = 1)");
  }
  if ($sticky_expressive_filter=='checked'){
    array_push($filter_exprs, "(expressive = 1)");
  }
  if ($sticky_play_with_rules_filter=='checked'){
    array_push($filter_exprs, "(play_with_rules = 1)");
  }
  if ($sticky_bio_filter=='checked'){
    array_push($filter_exprs, "(bio = 1)");
  }

  if (count($filter_exprs) > 0){
    $where_part = "WHERE " . implode(' AND ', $filter_exprs);
  }
  if($sticky_sortby_name == 'selected'){
    $order_part2 = "name;";
  }
  else if($sticky_sortby_sci_name=='selected'){
    $order_part2 = "sci_name;";
  }

  $query = $select_part . $where_part . $order_part . $order_part2;
  $result=exec_sql_query($db, $query);

  //get records
  $records=$result->fetchAll();
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
  <h1>Playful Plants Project</h1>
  <div class="align-right">
        <a href="/login"> Logout</a> <!--Have this button process the logout-->
    </div>
<div class="content">
  <aside>
  <form method="post" action="/" id="print_button" novalidate>
      <input type="submit" value="Print Catalog" name="print_submit" onclick="window.print()"/>
    </form>
    <!--FILTER FORM-->
    <form method="get" action="/admin" novalidate>
      <div class="form_element">
        <label for="sort_field">Sort By:</label>
        <select id="sort_field" name="sort">
          <option value="sortby_name" <?php echo htmlspecialchars($sticky_sortby_name)?>> Plant Name </option>
          <option value="sortby_sci_name" <?php echo htmlspecialchars($sticky_sortby_sci_name)?>> Scientific Name </option>
        </select>
      </div>
      <div class=columns>
        <div class="form_element">
          <input type="checkbox" id="exploratory_constructive_box" name="exploratory_constructive_box" <?php echo htmlspecialchars($sticky_exploratory_constructive_filter)?>/>
          <label for="exploratory_constructive_box">Exploratory Constructive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="exploratory_sensory_box" name="exploratory_sensory_box" <?php echo htmlspecialchars($sticky_exploratory_sensory_filter)?>/>
          <label for="exploratory_sensory_box">Exploratory Sensory Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="physical_box" name="physical_box" <?php echo htmlspecialchars($sticky_physical_filter)?>/>
          <label for="physical_box">Physical Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="imaginative_box" name="imaginative_box" <?php echo htmlspecialchars($sticky_imaginative_filter)?>/>
          <label for="imaginative_box">Imaginative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="restorative_box" name="restorative_box" <?php echo htmlspecialchars($sticky_restorative_filter)?>/>
          <label for="restorative_box">Restorative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="expressive_box" name="expressive_box" <?php echo htmlspecialchars($sticky_expressive_filter)?>/>
          <label for="expressive_box">Expressive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="play_with_rules_box" name="play_with_rules_box" <?php echo htmlspecialchars($sticky_play_with_rules_filter)?>/>
          <label for="play_with_rules_box">Play with Rules</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="bio_box" name="bio_box" <?php echo htmlspecialchars($sticky_bio_filter)?>/>
          <label for="bio_box">Bio Play</label>
        </div>
      </div>
      <div class="align-right">
        <input type="submit" value="Apply Changes" name="apply_changes_submit"/>
      </div>
    </form>

  </aside>

  <main>
    <h2> Playful Plants Catalog </h2>
    <?php if($delete_feedback){?>
      <div class="confirmation">
          <?php
          echo htmlspecialchars("Plant with Plant ID '". $deleted_pp_id);?></a>' was successfully deleted from the database.
      </div>
      <?php }
        ?>
    <div class="print_linebreak">
    <div class="rows">
    <?php
    $counter = 1;
    $counter_p = 1?>
    <div class="catalog_entry">
        <!--Image Source: Original Work (Mary Kolbas) -->
        <img src="/public/plus.jpg" alt="Plus sign in circle">
        <h3><a href="/addplant"> Add New Plant </a></h3>
    </div>
    <?php
    foreach($records as $record){
      $query_string = http_build_query(array(
        'pp_id' => $record['pp_id']
      ));
      ?>
      <?php if($counter_p==4) echo '<div class="print_linebreak">'; ?>
      <?php if($counter%2==0) echo '<div class="rows">'; ?>

      <div class="catalog_entry">
      <?php
            $result_documentstable = exec_sql_query(
            $db,
            "SELECT file_name AS 'documents.file_name' FROM documents WHERE (id=:plant_id);",
            array(
            ':plant_id' => $record['id']
            )
        )->fetchAll();
        ?>
      <div class="align-right">
      <form class="delete_form" method="post" action="/admin" id="delete<?php echo htmlspecialchars($record['id']);?>" novalidate>
          <input type="hidden" name="plant_id" value="<?php echo htmlspecialchars($record['id'])?>">
          <input class="delete_button" type="submit" value="Delete" name="delete_submit"/>
      </form> </div>
      <img class="admin_image" src = "/public/uploads/documents/<?php echo htmlspecialchars($result_documentstable[0]['documents.file_name']);?>.jpg" alt="Image of <?php echo htmlspecialchars($record['name']);?>">
        <h3><?php echo htmlspecialchars($record['name']); ?></h3>
        <h4><?php echo htmlspecialchars($record['sci_name']);?> </h4>
        <!--<div class='rows_links'>-->
        <a class="edit_link" href="/admin_plant?<?php echo $query_string; ?>"> Edit </a>

      </div>
    <?php if($counter%2!=0) echo "</div>" ?>
    <?php if($counter_p==3) echo "</div>" ?>
    <?php
    $counter=$counter+1;
    $counter_p=$counter_p+1;
    if($counter_p==5){$counter_p=1;
    }
    } ?> <!--closes foreach-->
    <?php if($counter%2!=0) echo "</div>";?>
    <?php if($counter_p==1||$counter_p==2||$counter_p==3||$counter_p==4) echo "</div>";?>
  </main>
</div>
</body>

</html>
