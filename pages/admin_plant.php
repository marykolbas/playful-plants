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

$name_feedback_class = 'hidden';
$sci_name_feedback_class = 'hidden';
$plant_id_feedback_class = 'hidden';
$sci_name_feedback_unique = 'hidden';
$plant_id_feedback_unique = 'hidden';

if (isset($_POST['edit_plant_submit'])) {

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
    //not done yet
    $result=True; //temp true to see if confirmation shows
    if($result){ //use to do confirmation message
      $result_edited=True;
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
    <div class="confirmation">
      <?php
      if($result_edited){
        echo htmlspecialchars("Plant with Plant ID '" . $plant_id . "' was successfully edited.");?>
      <?php } ?>

    </div>
    <a href="/"> Return to Catalog </a>
    <form method="post" action="/plant" id="addform" novalidate>
    <h2> Edit Existing Plant </h2>

      <div class="feedback <?php echo $name_feedback_class; ?>">Please enter the plant's name.</div>
      <div class="form_element">
        <label for="name_input">Plant Name:</label>
        <input type="text" id="name_input" name="name" value="3 Sisters-Corn"/>
      </div>
      <div class="feedback <?php echo $sci_name_feedback_class; ?>">Please enter the plant's scientific name.</div>
      <div class="feedback <?php echo $sci_name_feedback_unique; ?>">A plant with this scientific name already exists. Please enter a different scientific name.</div>
      <div class="form_element">
        <label for="sci_name_input">Scientific Name:</label>
        <input type="text" id="sci_name_input" name="sci_name" value="Red Mohawk Corn"/>
      </div>
      <div class="feedback <?php echo $plant_id_feedback_class; ?>">Please enter the Plant ID.</div>
      <div class="feedback <?php echo $plant_id_feedback_unique; ?>">A plant with this Plant ID already exists. Please enter a different Plant ID.</div>
      <div class="form_element">
        <label for="plant_id_input">Plant ID:</label>
        <input type="text" id="plant_id_input" name="plant_id" value="FE_07"/>
      </div>
      <div class="form_element">
        <img class="big_image" src="/public/temp_plant.jpg" alt="Drawing of Flower with words 'No Image' overlayed">
        Upload Image
      </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_constructive_box" name="is_exploratory_constructive"/>
          <label for="is_exploratory_constructive_box">Exploratory Constructive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_sensory_box" name="is_exploratory_sensory" checked/>
          <label for="is_exploratory_sensory_box">Exploratory Sensory Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_physical_box" name="is_physical" checked/>
          <label for="is_physical_box">Physical Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_imaginative_box" name="is_imaginative" checked />
          <label for="is_imaginative_box">Imaginative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_restorative_box" name="is_restorative" checked />
          <label for="is_restorative_box">Restorative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_expressive_box" name="is_expressive"  />
          <label for="is_expressive_box">Expressive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_play_with_rules_box" name="is_play_with_rules" checked />
          <label for="is_play_with_rules_box">Play with Rules</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_bio_box" name="is_bio" />
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
            <option value="class_vine" selected> Other </option>
          </select>
        </div>
        <div class="form_element">
          <label for="growth">Perennial/Annual:</label>
          <select id="growth" name="growth">
            <option value="season_none"> </option>
            <option value="perennial"> Perennial</option>
            <option value="annual" selected> Annual</option>
          </select>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullsun" name="fullsun" checked />
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
          <input type="text" id="hardiness" name="sci_name" value="4-9"/>
        </div>

      </div>

      <div class="align-right">
        <input type="submit" value="Save Changes" name="edit_plant_submit"/>
    </div>

    </form>
      </main>
</body>
</html>
