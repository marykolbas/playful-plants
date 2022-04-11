<?php
  //open database
  $db = init_sqlite_db('db/site.sqlite', 'db.init.sq()';

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
    $sticky_sortby_plant_id = '';
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
    $sticky_sortby_plant_id = ($_GET['sort']=='sortby_plant_id' ? 'selected' : '');
    $sticky_exploratory_constructive_filter = ($_GET['exploratory_constructive_box'] ? 'checked' : '');
    $sticky_exploratory_sensory_filter = ($_GET['exploratory_sensory_box'] ? 'checked' : '');
    $sticky_physical_filter = ($_GET['physical_box'] ? 'checked' : '');
    $sticky_imaginative_filter = ($_GET['imaginative_box'] ? 'checked' : '');
    $sticky_restorative_filter =($_GET['restorative_box'] ? 'checked' : '');
    $sticky_expressive_filter = ($_GET['expressive_box'] ? 'checked' : '');
    $sticky_play_with_rules_filter = ($_GET['play_with_rules_box'] ? 'checked' : '');
    $sticky_bio_filter = ($_GET['bio_box'] ? 'checked' : '');
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
    $where_part = "WHERE " . implode(' OR ', $filter_exprs);
  }
  if($sticky_sortby_name == 'selected'){
    $order_part2 = "name;";
  }
  else if($sticky_sortby_sci_name=='selected'){
    $order_part2 = "sci_name;";
  }
  else if($sticky_sortby_plant_id=='selected'){
    $order_part2 = "plant_id;";
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
    <!--FILTER FORM-->
    <form method="get" action="/" novalidate>
      <div class="form_element">
        <label for="sort_field">Sort By:</label>
        <select id="sort_field" name="sort">
          <option value="sortby_name" <?php echo htmlspecialchars($sticky_sortby_name)?>> Plant Name </option>
          <option value="sortby_sci_name" <?php echo htmlspecialchars($sticky_sortby_sci_name)?>> Scientific Name </option>
          <option value="sortby_plant_id" <?php echo htmlspecialchars($sticky_sortby_plant_id)?>> Plant ID </option>
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
    <div class="rows">
      <div class="catalog_entry">
        <!--Image Source: Original Work (Mary Kolbas) -->
        <img src="/public/plus.jpg" alt="Plus sign in circle">
        <h3><a href="/addplant"> Add New Plant </a></h3>
      </div>
      <div class="catalog_entry">
        <!--Image Source: Original Work (Mary Kolbas) -->
        <img src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        <h3>3 Sisters-Corn</h3>
        <h4>Red Mohawk Corn</h4>
        <a href="/admin_plant"> Edit </a>
      </div>
    </div>
    <div class="rows">
      <div class="catalog_entry">
        <img src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        <h3>American Groundnut</h3>
        <h4 class="sciname">Apius americana</h4>
        <a href="/admin_plant"> Edit </a>
      </div>
      <div class="catalog_entry">
        <img src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        <h3>Common Nasturtiums</h3>
        <h4 class="sciname">Tropaeolum (group)</h4>
        <a href="/admin_plant"> Edit </a>
      </div>
    </div>
    <div class="rows">
      <div class="catalog_entry">
        <img src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        <h3>Downy skullcap</h3>
        <h4 class="sciname">Scutellaria incana</h4>
        <a href="/admin_plant"> Edit </a>
      </div>
    </div>
  </main>
</div>
</body>

</html>