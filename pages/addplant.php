<?php
  //open database
  $db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

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
  $hardiness='';

  $name_feedback_class = 'hidden';
  $sci_name_feedback_class = 'hidden';
  $pp_id_feedback_class = 'hidden';
  $sci_name_feedback_unique = 'hidden';
  $pp_id_feedback_unique = 'hidden';

#ADD PLANT FORM
if(!$form_valid){
    $sticky_name= '';
    $sticky_sci_name = '';
    $sticky_pp_id = '';
    $sticky_exploratory_constructive = '';
    $sticky_exploratory_sensory = '';
    $sticky_physical = '';
    $sticky_imaginative = '';
    $sticky_imaginative = '';
    $sticky_restorative = '';
    $sticky_expressive = '';
    $sticky_play_with_rules= '';
    $sticky_bio = '';
    $sticky_hardiness = '';
  }

if (isset($_POST['add_plant_submit'])) {

  $name = trim($_POST['name']); // untrusted
  $sci_name = trim($_POST['sci_name']); // untrusted
  $pp_id = strtoupper(trim($_POST['pp_id'])); // untrusted
  $exploratory_constructive = ($_POST['is_exploratory_constructive'] ? 'checked' : '');
  $exploratory_sensory = ($_POST['is_exploratory_sensory'] ? 'checked' : '');
  $physical = ($_POST['is_physical'] ? 'checked' : '');
  $imaginative = ($_POST['is_imaginative'] ? 'checked' : '');
  $restorative = ($_POST['is_restorative'] ? 'checked' : '');
  $expressive = ($_POST['is_expressive'] ? 'checked' : '');
  $play_with_rules = ($_POST['is_play_with_rules'] ? 'checked' : '');
  $bio = ($_POST['is_bio'] ? 'checked' : '');
  $hardiness = ($_POST['hardiness']);

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
  if (empty($pp_id)) {
    $form_valid = False;
    $pp_id_feedback_class = '';
  }
  else{
    $records = exec_sql_query(
      $db,
      "SELECT * FROM plants WHERE (pp_id = :pp_id);",
      array(
        ':pp_id' => $pp_id
      )
    )-> fetchAll();
    if(count($records)>0){
      $form_valid = False;
      $pp_id_feedback_unique = True;
    }
  }


  if($form_valid){
    $result = exec_sql_query(
      $db,
      "INSERT INTO plants (name, sci_name, pp_id, exploratory_constructive, exploratory_sensory, physical, imaginative, restorative, expressive, play_with_rules, bio, hardiness_level) VALUES (:name, :sci_name, :pp_id, :ex_con, :ex_sen, :phys, :imag, :rest, :expr, :rules, :bio,:hardiness);",
      array(
        ':name' => $name,
        ':sci_name' => $sci_name,
        ':pp_id' => $pp_id,
        ':ex_con' => $exploratory_constructive == 'checked' ? '1' : '0',
        ':ex_sen' => $exploratory_sensory == 'checked' ? '1' : '0',
        ':phys' => $physical == 'checked' ? '1' : '0',
        ':imag' => $imaginative == 'checked' ? '1' : '0',
        ':rest' => $restorative == 'checked' ? '1' : '0',
        ':expr' => $expressive == 'checked' ? '1' : '0',
        ':rules' => $play_with_rules == 'checked' ? '1' : '0',
        ':bio' => $bio == 'checked' ? '1' : '0',
        ':hardiness' => $hardiness
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
    $sticky_pp_id = $pp_id;
    $sticky_exploratory_constructive = $exploratory_constructive;
    $sticky_exploratory_sensory = $exploratory_sensory;
    $sticky_physical = $physical;
    $sticky_imaginative = $imaginative;
    $sticky_restorative = $restorative;
    $sticky_expressive = $expressive;
    $sticky_play_with_rules = $play_with_rules;
    $sticky_bio = $bio;
    $sticky_hardiness = $hardiness;
  }
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
<main class="center">
<h1>Playful Plants Project</h1>
<div class="align-right">
   <a href="/login"> Logout</a> <!--Have this button process the logout-->
</div>
<a href="/"> Return to Catalog </a>
<!--ADD PLANT FORM-->
<form method="post" action="/addplant" id="addform" novalidate>
    <h2> Add a New Plant </h2>
    <div class="confirmation">
      <?php
      if($result_inserted){
        echo htmlspecialchars("Plant with Plant ID '" . $pp_id . "' was successfully added to the database.");
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
      <div class="feedback <?php echo $pp_id_feedback_class; ?>">Please enter the Plant ID.</div>
      <div class="feedback <?php echo $pp_id_feedback_unique; ?>">A plant with this Plant ID already exists. Please enter a different Plant ID.</div>
      <div class="form_element">
        <label for="pp_id_input">Plant ID:</label>
        <input type="text" id="pp_id_input" name="pp_id" value="<?php echo htmlspecialchars($sticky_pp_id)?>"/>
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
        <div class="form_element">
          <label for="classification">General Classification:</label>
          <select id="classification" name="class">
            <option value="class_none"> </option>
            <option value="class_shrub"> Shrub </option>
            <option value="class_grass"> Grass </option>
            <option value="class_vine"> Vine </option>
            <option value="class_vine" > Tree </option>
            <option value="class_vine" > Groundcover </option>
            <option value="class_vine" > Other </option>
          </select>
        </div>
        <div class="form_element">
          <label for="season">Perennial/Annual:</label>
          <select id="season" name="season">
            <option value="season_none"> </option>
            <option value="perennial"> Perennial</option>
            <option value="annual" > Annual</option>
          </select>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullsun" name="fullsun" />
          <label for="fullsun">Full Sun </label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="partialshade" name="partialshade"  />
          <label for="partialshade">Partial Shade </label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullshade" name="fullshade"/>
          <label for="fullshade">Full Shade </label>
        </div>
        <div class="form_element">
          <label for="hardiness">Hardiness Level</label>
          <input type="text" id="hardiness" name="hardiness" value="<?php echo htmlspecialchars($sticky_hardiness)?>"/>
      </div>
      </div>
      <div class="align-right">
        <input type="submit" value="Add Plant" name="add_plant_submit"/>
    </div>
    </form>
    </main>
</body>
</html>
