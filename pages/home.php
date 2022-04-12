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
<main>
  <h1>Playful Plants Project</h1>
  <div class="align-right">
        <a href="/login"> Log-in </a>
    </div>
<div class="content">
  <aside>
      <p id="instructions">Sort and Filter catalog contents by selecting options below, then click "Apply Changes".</p>
  <form method="post" action="/" id="print_button" novalidate>
      <input type="submit" value="Print Catalog" name="print_submit" onclick="window.print()"/>
    </form>
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

    <label for="classification"> Growth Cycle</label>
        <div class="form_element">
          <input type="checkbox" id="shrub" name="classification" <?php echo htmlspecialchars($sticky_shrub)?>/>
          <label for="shrub">Shrub</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="grass" name="classification" <?php echo htmlspecialchars($sticky_grass)?>/>
          <label for="grass">Grass</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="vine" name="classification" <?php echo htmlspecialchars($sticky_vine)?>/>
          <label for="vine">Vine</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="tree" name="classification" <?php echo htmlspecialchars($sticky_tree)?>/>
          <label for="tree">Tree</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="groundcover" name="classification" <?php echo htmlspecialchars($sticky_groundcover)?>/>
          <label for="groundcover">Groundcover</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="other" name="classification" <?php echo htmlspecialchars($sticky_other)?>/>
          <label for="other">Other</label>
        </div>

        <label for="growth"> Growth Cycle</label>
        <div class="form_element">
          <input type="checkbox" id="annual" name="growth" <?php echo htmlspecialchars($sticky_annual)?>/>
          <label for="annual">Annual</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="perennial" name="growth" <?php echo htmlspecialchars($sticky_perennial)?>/>
          <label for="perennial">Perennial</label>
        </div>

        <label for="sunlight"> Sunlight </label>
        <div class="form_element">
          <input type="checkbox" id="fullsun" name="sunlight" <?php echo htmlspecialchars($sticky_fullsun)?>/>
          <label for="fullsun">Full Sun</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="partialshade" name="sunlight" <?php echo htmlspecialchars($sticky_partialshade)?>/>
          <label for="partialshade">Partial Shade</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullshade" name="sunlight" <?php echo htmlspecialchars($sticky_fullshade)?>/>
          <label for="fullshade">Full Shade</label>
        </div>
        <div class="form_element">
          <label for="fullshade">Hardiness Level: </label>
          <input type="text" id="hardiness" name="hardiness" <?php echo htmlspecialchars($sticky_hardiness)?>/>
        </div> <!--Change this to dropdowns or categorical?-->

      <div class="align-right">
        <input type="submit" value="Apply Changes" name="apply_changes_submit"/>
      </div>
    </form>

  </aside>

  <main>
    <h2> Playful Plants Catalog </h2>

    <?php
    $counter = 0;
    foreach($records as $record){
      $query_string = http_build_query(array(
        'pp_id' => $record['pp_id']
      ));
      ?>
      <?php if($counter%2==0) echo '<div class="rows">'; ?>
        <div class="catalog_entry">
          <!--onerror source: https://www.w3schools.com/jsref/event_onerror.asp-->
          <img src = "/public/seed_images/<?php echo htmlspecialchars($record['pp_id'])?>.jpg" onerror="this.onerror=null; this.src='/public/temp_plant.jpg'" alt="Image of "<?php echo htmlspecialchars($record['name']);?>>
          <h3><a href="/plant?<?php echo $query_string; ?>"><?php echo htmlspecialchars($record['name']); ?></a></h3>
          <h4 class="sciname"><?php echo htmlspecialchars($record['sci_name']); ?></h4>
        </div>
      <?php if($counter%2!=0) echo "</div>" ?>
    <?php
    $counter=$counter+1;
    } ?>
  </main>
</div>
</main>
</body>

</html>
