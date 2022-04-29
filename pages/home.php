<?php

  #starting variables
  $name = '';
  $sci_name = '';
  $pp_id = '';
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
  $pp_id_feedback_class = 'hidden';
  $sci_name_feedback_unique = 'hidden';
  $pp_id_feedback_unique = 'hidden';


#FILTER FORM
  #starting variables for filter
  $perennial= '';
  $annual = '';
  $fullsun = '';
  $partialshade = '';
  $fullshade = '';
  $shrub = '';
  $grass = '';
  $vine = '';
  $tree = '';
  $flower = '';
  $groundcover = '';
  $other = '';

  #sticky values for when form isnt submitted
  if(isset($_GET['apply_changes_submit'])){
    $filter_submitted = True;
  }
  if(!$filter_submitted){
    $sticky_perennial = '';
    $sticky_annual = '';
    $sticky_fullsun = '';
    $sticky_partialshade = '';
    $sticky_fullshade = '';
    $sticky_shrub = '';
    $sticky_grass = '';
    $sticky_vine = '';
    $sticky_tree = '';
    $sticky_flower = '';
    $sticky_groundcover = '';
    $sticky_other = '';
  }
  else{
    $sticky_shrub= ($_GET['shrub']==6 ? 'checked' : ''); #untrusted?
    $sticky_grass = ($_GET['grass']==7 ? 'checked' : '');
    $sticky_vine = ($_GET['vine']==8 ? 'checked' : '');
    $sticky_tree = ($_GET['tree']==9 ? 'checked' : '');
    $sticky_flower = ($_GET['flower']==10 ? 'checked' : '');
    $sticky_groundcover = ($_GET['groundcover']==11 ? 'checked' : '');
    $sticky_other = ($_GET['other']==12 ? 'checked' : '');
    $sticky_perennial = ($_GET['perennial']==1 ? 'checked' : '');
    $sticky_annual= ($_GET['annual']==2 ? 'checked' : '');
    $sticky_fullsun = ($_GET['fullsun']==3 ? 'checked' : '');
    $sticky_partialshade = ($_GET['partialshade']==4 ? 'checked' : '');
    $sticky_fullshade = ($_GET['fullshade']==5 ? 'checked' : '');
    $sticky_hardiness = ($_GET['hardiness']);

  }

  //query table )
  $select_part = "SELECT * FROM plants INNER JOIN entry_tags ON (plants.id = entry_tags.plant_id) ";
  $order_part = " GROUP BY plants.id ORDER BY ";
  $where_part = "";
  $order_part2 = "name;";
  //create list for SQL conditional expressions:
  $filter_exprs = array();
  if ($sticky_shrub=='checked'){
    //append SQl conditional expression to list:
    array_push($filter_exprs, "(entry_tags.tag_id = 6)");
  }
  if ($sticky_grass=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 7)");
  }
  if ($sticky_vine=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 8)");
  }
  if ($sticky_tree=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 9)");
  }
  if ($sticky_flower=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 10)");
  }
  if ($sticky_groundcover=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 11)");
  }
  if ($sticky_other=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 12)");
  }
  if ($sticky_perennial=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 1)");
  }
  if ($sticky_annual=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 2)");
  }
  if ($sticky_fullsun=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 3)");
  }
  if ($sticky_partialshade=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 4)");
  }
  if ($sticky_fullshade=='checked'){
    array_push($filter_exprs, "(entry_tags.tag_id = 5)");
  }
  if(isset($sticky_hardiness)){
    array_push($filter_exprs, "(plants.hardiness_level = '" . $sticky_hardiness . "')");
  }

  if (count($filter_exprs) > 0){
    $where_part = "WHERE " . implode(' OR ', $filter_exprs);
  }
  if($sticky_sortby_name == 'checked'){
    $order_part2 = "name;";
  }
  else if($sticky_sortby_sci_name=='checked'){
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
<div class="rows_titlenav">
  <h1>Playful Plants Project</h1>
  <?php if(!is_user_logged_in() || !$is_admin){?>
    <a class="login_alone" href="/login"> Log-in </a>
  <?php } ?>
</div>
<div class="content">
  <aside>
      <p id="instructions">Sort and Filter catalog contents by selecting options below, then click "Apply Changes". </p><p>Filtering by multiple tags will display any plants that fit at least one of the selected fields.</p>

    <!--FILTER FORM-->
    <form method="get" action="/" novalidate>
      <div class="form_element">
        <label for="sort_field">Sort By:</label>
        <select id="sort_field" name="sort">
          <option value="sortby_name" <?php echo htmlspecialchars($sticky_sortby_name)?>> Plant Name </option>
          <option value="sortby_sci_name" <?php echo htmlspecialchars($sticky_sortby_sci_name)?>> Scientific Name </option>
        </select>
      </div>

    <!--<label for="classification"> Classification</label>-->
    Classification
        <div class="form_element">
          <input type="checkbox" id="shrub" value=6 name="shrub" <?php echo htmlspecialchars($sticky_shrub)?>/>
          <label for="shrub">Shrub</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="grass" name="grass" value=7 <?php echo htmlspecialchars($sticky_grass)?>/>
          <label for="grass">Grass</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="vine" name="vine" value=8 <?php echo htmlspecialchars($sticky_vine)?>/>
          <label for="vine">Vine</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="tree" value=9 name="tree" <?php echo htmlspecialchars($sticky_tree)?>/>
          <label for="tree">Tree</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="flower" value=10 name="flower" <?php echo htmlspecialchars($sticky_flower)?>/>
          <label for="flower">Flower</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="groundcover" value=11 name="groundcover" <?php echo htmlspecialchars($sticky_groundcover)?>/>
          <label for="groundcover">Groundcover</label>
        </div>

        <div class="form_element">
          <input type="checkbox" id="other" name="other" value=12 <?php echo htmlspecialchars($sticky_other)?>/>
          <label for="other">Other</label>
        </div>

        <!--<label for="growth"> Growth Cycle</label>-->
        Growth Cycle
        <div class="form_element">
          <input type="checkbox" id="annual" name="annual" value=2 <?php echo htmlspecialchars($sticky_annual)?>/>
          <label for="annual">Annual</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="perennial" name="perennial" value=1 <?php echo htmlspecialchars($sticky_perennial)?>/>
          <label for="perennial">Perennial</label>
        </div>

        <!--<label for="sunlight"> Sunlight </label>-->
        Sunlight
        <div class="form_element">
          <input type="checkbox" id="fullsun" name="fullsun" value=3<?php echo htmlspecialchars($sticky_fullsun)?>/>
          <label for="fullsun">Full Sun</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="partialshade" name="partialshade" value=4 <?php echo htmlspecialchars($sticky_partialshade)?>/>
          <label for="partialshade">Partial Shade</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullshade" name="fullshade" value=5 <?php echo htmlspecialchars($sticky_fullshade)?>/>
          <label for="fullshade">Full Shade</label>
        </div>
        <div class="form_element">
          <label for="hardiness">Hardiness Level: </label>
          <input type="text" id="hardiness" name="hardiness" <?php echo htmlspecialchars($sticky_hardiness)?>/>
        </div>

      <div class="align-right">
        <input type="submit" value="Apply Changes" name="apply_changes_submit"/>
      </div>
    </form>

  </aside>

  <main>
  <div class="rows_titlenav">
    <h2> Playful Plants Catalog </h2>
    <div>
      <?php if(is_user_logged_in() && $is_admin){?>
          <ul class="nav_bar">
            <li class="nav_selected"><a href="/">Consumer View</a></li>
            <li><a href="/admin">Admin View</a></li>
            <li><a href="<?php echo logout_url();?>">Logout</a></li>
          </ul>
      <?php }?>
    </div>
  </div>
    <?php
    $counter = 0;
    $printcounter=0;
    foreach($records as $record){
      $query_string = http_build_query(array(
        'pp_id' => $record['pp_id']
      ));
      $plant_id = $record['plant_id'];
      //gets tags for this plant for filtering
      $result_tags = exec_sql_query(
        $db,
        "SELECT plants.id AS 'plants.id', entry_tags.tag_id AS 'entry_tags.tag_id', entry_tags.plant_id AS 'entry_tags.plant_id' FROM plants INNER JOIN entry_tags ON (plants.id = entry_tags.plant_id) WHERE (plants.id=:plant_id);",
        array(
          ':plant_id' => $record['plant_id']
        )
      )->fetchAll();
      // $result_tags_array = $result_tags['entry_tags.plant_id'];
      // if(in_array(1,$results_tags_array))

      //gets the name of this plant's image
      $result_documentstable = exec_sql_query(
        $db,
        "SELECT file_name AS 'documents.file_name', file_ext AS 'documents.file_ext' FROM documents WHERE (id=:plant_id);",
        array(
          ':plant_id' => $plant_id //$record['id']
        )
      )->fetchAll();
      ?>
      <?php if($counter%2==0) echo '<div class="rows">'; ?>
        <div class="catalog_entry">
          <img src="/public/uploads/documents/<?php echo htmlspecialchars($result_documentstable[0]['documents.file_name']);?>.<?php echo htmlspecialchars($result_documentstable[0]['documents.file_ext']);?>" alt="Image of <?php echo htmlspecialchars($record['name']);?>">
          <h3><a href="/plant?<?php echo $query_string; ?>"><?php echo htmlspecialchars($record['name']); ?></a></h3>
          <h4 class="sciname"><?php echo htmlspecialchars($record['sci_name']); ?></h4>
        </div>
      <?php if($counter%2!=0) echo "</div>" ?>
    <?php
    $counter=$counter+1;
    } ?>
  </div>

  </main>
  </div>
</body>

</html>
