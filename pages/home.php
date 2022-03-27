<?php
  //open database
  $db = open_sqlite_db('tmp/site.sqlite');

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

#ADD PLANT FORM
  if(!$form_valid){
    $sticky_name= '';
    $sticky_sci_name = '';
    $sticky_plant_id = '';
    $sticky_exploratory_constructive = '';
    $sticky_exploratory_sensory = '';
    $sticky_physical = '';
    $sticky_imaginative = '';
    $sticky_imaginative = '';
    $sticky_restorative = '';
    $sticky_expressive = '';
    $sticky_play_with_rules= '';
    $sticky_bio = '';
  }

if (isset($_POST['add_plant_submit'])) {

  $name = trim($_POST['name']); // untrusted
  $sci_name = trim($_POST['sci_name']); // untrusted
  $plant_id = strtoupper(trim($_POST['plant_id'])); // untrusted
  $exploratory_constructive = ($_POST['is_exploratory_constructive'] ? 'checked' : '');
  $exploratory_sensory = ($_POST['is_exploratory_sensory'] ? 'checked' : '');
  $physical = ($_POST['is_physical'] ? 'checked' : '');
  $imaginative = ($_POST['is_imaginative'] ? 'checked' : '');
  $restorative = ($_POST['is_restorative'] ? 'checked' : '');
  $expressive = ($_POST['is_expressive'] ? 'checked' : '');
  $play_with_rules = ($_POST['is_play_with_rules'] ? 'checked' : '');
  $bio = ($_POST['is_bio'] ? 'checked' : '');

  $form_valid = True;

  if (empty($name)) {
    $form_valid = False;
    $name_feedback_class = '';
  }
  if (empty($sci_name)) {
    $form_valid = False;
    $sci_name_feedback_class = '';
  }
  else{
    //check if sci_name is unique
    $records = exec_sql_query(
      $db,
      "SELECT * FROM plants WHERE (sci_name = :sci_name);",
      array(
        ':sci_name' => $sci_name
      )
    )-> fetchAll();
    if(count($records)>0){
      $form_valid = False;
      $sci_name_feedback_unique = True;
    }
  }
  if (empty($plant_id)) {
    $form_valid = False;
    $plant_id_feedback_class = '';
  }
  else{
    $records = exec_sql_query(
      $db,
      "SELECT * FROM plants WHERE (plant_id = :plant_id);",
      array(
        ':plant_id' => $plant_id
      )
    )-> fetchAll();
    if(count($records)>0){
      $form_valid = False;
      $plant_id_feedback_unique = True;
    }
  }


  if($form_valid){
    $result = exec_sql_query(
      $db,
      "INSERT INTO plants (name, sci_name, plant_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio) VALUES (:name, :sci_name, :plant_id, :ex_con, :ex_sen, :phys, :imag, :rest, :expr, :rules, :bio);",
      array(
        ':name' => $name,
        ':sci_name' => $sci_name,
        ':plant_id' => $plant_id,
        ':ex_con' => $exploratory_constructive == 'checked' ? '1' : '0',
        ':ex_sen' => $exploratory_sensory == 'checked' ? '1' : '0',
        ':phys' => $physical == 'checked' ? '1' : '0',
        ':imag' => $imaginative == 'checked' ? '1' : '0',
        ':rest' => $restorative == 'checked' ? '1' : '0',
        ':expr' => $expressive == 'checked' ? '1' : '0',
        ':rules' => $play_with_rules == 'checked' ? '1' : '0',
        ':bio' => $bio == 'checked' ? '1' : '0'
      )
    );
    if($result){ //use to do confirmation message?
      $result_inserted=True;
    }
  }
  else{
    // set sticky values
    $sticky_name=$name;
    $sticky_sci_name = $sci_name;
    $sticky_plant_id = $plant_id;
    $sticky_exploratory_constructive = $exploratory_constructive;
    $sticky_exploratory_sensory = $exploratory_sensory;
    $sticky_physical = $physical;
    $sticky_imaginative = $imaginative;
    $sticky_restorative = $restorative;
    $sticky_expressive = $expressive;
    $sticky_play_with_rules = $play_with_rules;
    $sticky_bio = $bio;
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
<div class="rows">
  <aside>
  <form method="post" action="/" id="print_button" novalidate>
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
    <!--ADD PLANT FORM-->

    <form method="post" action="/" id="addform" novalidate>
    <h2> Add a New Plant </h2>
    <div class="confirmation">
      <?php
      if($result_inserted){
        echo htmlspecialchars("Plant with Plant ID '" . $plant_id . "' was successfully added to the database.");
      }
      ?>
    </div>
    <div class=columns>
      <div class="feedback <?php echo $name_feedback_class; ?>">Please enter the plant's name.</div>
      <div class="form_element">
        <label for="name_input">Plant Name:</label>
        <input type="text" id="name_input" name="name" value="<?php echo htmlspecialchars($sticky_name)?>"/>
      </div>
      <div class="feedback <?php echo $sci_name_feedback_class; ?>">Please enter the plant's scientific name.</div>
      <div class="feedback <?php echo $sci_name_feedback_unique; ?>">A plant with this scientific name already exists. Please enter a different scientific name.</div>
      <div class="form_element">
        <label for="sci_name_input">Scientific Name:</label>
        <input type="text" id="sci_name_input" name="sci_name" value="<?php echo htmlspecialchars($sticky_sci_name)?>"/>
      </div>
      <div class="feedback <?php echo $plant_id_feedback_class; ?>">Please enter the Plant ID.</div>
      <div class="feedback <?php echo $plant_id_feedback_unique; ?>">A plant with this Plant ID already exists. Please enter a different Plant ID.</div>
      <div class="form_element">
        <label for="plant_id_input">Plant ID:</label>
        <input type="text" id="plant_id_input" name="plant_id" value="<?php echo htmlspecialchars($sticky_plant_id)?>"/>
      </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_constructive_box" name="is_exploratory_constructive" <?php echo htmlspecialchars($sticky_exploratory_constructive)?>/>
          <label for="is_exploratory_constructive_box">Exploratory Constructive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_sensory_box" name="is_exploratory_sensory" <?php echo htmlspecialchars($sticky_exploratory_sensory)?>/>
          <label for="is_exploratory_sensory_box">Exploratory Sensory Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_physical_box" name="is_physical" <?php echo htmlspecialchars($sticky_physical)?>/>
          <label for="is_physical_box">Physical Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_imaginative_box" name="is_imaginative" <?php echo htmlspecialchars($sticky_imaginative)?> />
          <label for="is_imaginative_box">Imaginative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_restorative_box" name="is_restorative" <?php echo htmlspecialchars($sticky_restorative)?> />
          <label for="is_restorative_box">Restorative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_expressive_box" name="is_expressive" <?php echo htmlspecialchars($sticky_expressive)?> />
          <label for="is_expressive_box">Expressive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_play_with_rules_box" name="is_play_with_rules" <?php echo htmlspecialchars($sticky_play_with_rules)?> />
          <label for="is_play_with_rules_box">Play with Rules</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_bio_box" name="is_bio" <?php echo htmlspecialchars($sticky_bio)?> />
          <label for="is_bio_box">Bio Play</label>
        </div>
      </div>
      <div class="align-right">
        <input type="submit" value="Add Plant" name="add_plant_submit"/>
    </div>
    </form>
  </aside>

  <main class='print'>
    <h2> Playful Plants Catalog </h2>
    <table>
      <tr>
        <th>Name</th>
        <th>Scientific Name</th>
        <th>Plant ID</th>
        <th>Play Type</th>
      </tr>
      <?php foreach($records as $record){?>
        <tr>
          <td><?php echo htmlspecialchars($record['name']); ?></td>
          <td><?php echo htmlspecialchars($record['sci_name']); ?></td>
          <td><?php echo htmlspecialchars($record['plant_id']); ?></td>
          <?php
            //list for all play types of this particular plant entry
            $play_types = array();
            if ($record['exploratory_constructive'] == '1'){
              array_push($play_types, "Exploratory Constructive Play");
            }
            if ($record['exploratory_sensory'] == '1'){
              array_push($play_types, "Exploratory Sensory Play");
            }
            if ($record['physical'] == '1'){
              array_push($play_types, "Physical Play");
            }
            if ($record['imaginative'] == '1'){
              array_push($play_types, "Imaginative Play");
            }
            if ($record['restorative'] =='1'){
              array_push($play_types, "Restorative Play");
            }
            if ($record['expressive'] == '1'){
              array_push($play_types, "Expressive Play");
            }
            if ($record['play_with_rules'] == '1'){
              array_push($play_types, "Play With Rules");
            }
            if ($record['bio'] == '1'){
              array_push($play_types, "Bio Play");
            }

            if (count($play_types) > 0){
              $play_types_complete = implode(', ', $play_types);
            }
            else{
              $play_types_complete = "";
            }
            ?>
          <td>
            <?php echo htmlspecialchars($play_types_complete)?>
          </td>
        </tr>
      <?php }?>
    </table>
  </main>
</div>
</body>

</html>
