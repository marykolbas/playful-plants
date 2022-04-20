<?php
//open database
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

$plant=$_GET['pp_id'];
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
$exploratory_constructive = ($result[0]['exploratory_constructive']? 'checked' : '');
$exploratory_sensory = ($result[0]['exploratory_sensory']? 'checked' : '');
$physical = ($result[0]['physical']? 'checked' : '');
$imaginative = ($result[0]['imaginative']? 'checked' : '');
$restorative = ($result[0]['restorative']? 'checked' : '');
$expressive = ($result[0]['expressive']? 'checked' : '');
$play_with_rules = ($result[0]['play_with_rules']? 'checked' : '');
$bio = ($result[0]['bio']? 'checked' : '');

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


//Set to empty string (so that the foreach conditions work)
$is_shrub='';
$is_grass = '';
$is_vine='';
$is_tree = '';
$is_flower = '';
$is_groundcover = '';
$is_other = '';
$is_perennial = '';
$is_annual = '';
$is_fullsun = '';
$is_partialshade = '';
$is_fullshade = '';

//check which tags are selected for this plant
foreach($tags as $tag){
  $is_shrub= (($tag['tags.name'] == 'Classification: Shrub') || ($is_shrub=="selected") ? 'selected' : '');
  $is_grass= (($tag['tags.name'] == 'Classification: Grass') || ($is_grass=="selected")? 'selected' : '');
  $is_vine= (($tag['tags.name'] == 'Classification: Vine') || ($is_vine=="selected") ? 'selected' : '');
  $is_tree = (($tag['tags.name'] == 'Classification: Tree') || ($is_tree=="selected") ? 'selected' : '');
  $is_flower= (($tag['tags.name'] == 'Classification: Flower') || ($is_flower=="selected") ? 'selected' : '');
  $is_groundcover= (($tag['tags.name'] == 'Classification: Groundcover') || ($is_groundcover=="selected") ? 'selected' : '');
  $is_other= (($tag['tags.name'] == 'Classification: Other') || ($is_other=="selected") ? 'selected' : '');
  $is_perennial = (($tag['tags.name'] == 'Growth: Perennial') || ($is_perennial=="selected") ? 'selected' : '');
  $is_annual = (($tag['tags.name'] == 'Growth: Annual') || ($is_annual=="selected") ? 'selected' : '');
  $is_fullsun = (($tag['tags.name'] == 'Sun: Full Sun') || ($is_fullsun=="checked") ? 'checked' : '');
  $is_partialshade= (($tag['tags.name'] == 'Sun: Partial Shade') || ($is_partialshade=="checked") ? 'checked' : '');
  $is_fullshade = (($tag['tags.name'] == 'Sun: Full Shade') || ($is_fullshade=="checked") ? 'checked' : '');

}



$name_feedback_class = 'hidden';
$sci_name_feedback_class = 'hidden';
$pp_id_feedback_class = 'hidden';
$sci_name_feedback_unique = 'hidden';
$pp_id_feedback_unique = 'hidden';

$name_feedback_class = 'hidden';
$sci_name_feedback_class = 'hidden';
$pp_id_feedback_class = 'hidden';
$sci_name_feedback_unique = 'hidden';
$pp_id_feedback_unique = 'hidden';

if (isset($_POST['edit_plant_submit'])) {

  $name = trim($_POST['name']); // untrusted
  $sci_name = trim($_POST['sci_name']); // untrusted
  $pp_id = strtoupper(trim($_POST['plant_id'])); // untrusted
  $exploratory_constructive = (isset($_POST['is_exploratory_constructive']) ? 'checked' : '');
  $exploratory_sensory = (isset($_POST['is_exploratory_sensory']) ? 'checked' : '');
  $physical = (isset($_POST['is_physical']) ? 'checked' : '');
  $imaginative = (isset($_POST['is_imaginative']) ? 'checked' : '');
  $restorative = (isset($_POST['is_restorative']) ? 'checked' : '');
  $expressive = (isset($_POST['is_expressive']) ? 'checked' : '');
  $play_with_rules = (isset($_POST['is_play_with_rules'])? 'checked' : '');
  $bio = (isset($_POST['is_bio']) ? 'checked' : '');

  $classification = $_POST['class']; //will return the value (id) of that tag
  $growth = $_POST['growth'];
  $fullsun = $_POST['fullsun'];
  $partialshade = $_POST['partialshade'];
  $fullshade = $_POST['fullshade'];
  $tags_array = array_filter(array($classification, $growth, $fullsun, $partialshade, $fullshade));

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
      "SELECT * FROM plants WHERE (id = :plant_id);",
      array(
        ':plant_id' => $pp_id
      )
    )-> fetchAll();
    if(count($records)>0){
      $form_valid = False;
      $pp_id_feedback_unique = True;
    }
  }


  //as 1 or 0 to use for update array
  $ex_con_up = $exploratory_constructive == 'checked' ? '1' : '0';
  $ex_sen_up = ($exploratory_sensory == 'checked' ? '1' : '0');
  $phys_up = ($physical == 'checked' ? '1' : '0');
  $imag_up = ($imaginative == 'checked' ? '1' : '0');
  $rest_up = ($restorative == 'checked' ? '1' : '0');
  $expr_up = ($expressive == 'checked' ? '1' : '0');
  $rules_up = $play_with_rules == 'checked' ? '1' : '0';
  $bio_up = ($bio == 'checked' ? '1' : '0');

  if($form_valid){
    //not done yet
    $result = exec_sql_query(
      $db,
      "UPDATE plants SET name = :name, sci_name = :sciname, pp_id =:pp_id, exploratory_constructive = :ex_con, exploratory_sensory=:ex_sen, physical=:phys, imaginative=:imag, restorative=:rest, expressive=:expr, play_with_rules=:rules, bio=:bio, hardiness_level=:hardiness WHERE(id=:plant_id)",
      array(
        ':name' => $name,
        ':sci_name' => $sci_name,
        ':pp_id' => $plant,
        ':ex_con' => $ex_con_up,
        ':ex_sen' => $ex_sen_up,
        ':phys' => $phys_up,
        ':imag' => $imag_up,
        ':rest' => $rest_up,
        ':expr' => $expr_up,
        ':rules' => $rules_up,
        ':bio' => $bio_up,
        ':hardiness' => $hardiness,
        ':plant_id' => $plant_id
      )
    );
    // //delete all entrytags associated with this plant
    // $delete_old = exec_sql_query(
    //   $db,
    //   "DELETE FROM entry_tags WHERE(plant_id=:plant_id)",
    //   array(
    //     ':plant_id' => $plant_id
    //   )
    // );
    // //re-add new tags
    // foreach($tags_array as $tag){
    //   $result_tags = exec_sql_query(
    //     $db,
    //     "INSERT INTO entry_tags (plant_id, tag_id) VALUES (:plant_id, :tag_id);",
    //     array(
    //       ':plant_id' => $plant_id,
    //       ':tag_id' => $tag
    //     )
    //   );
    // }
    if($result ){ //use to do confirmation message && $result_tags
      $result_edited=True;
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
        echo htmlspecialchars("Plant with Plant ID '" . $pp_id . "' was successfully edited.");?>
      <?php } ?>

    </div>
    <a href="/"> Return to Catalog </a>
    <?php
    $query_string = http_build_query(array(
        'pp_id' => $plant
      ));
      ?>
    <form method="post" action="/admin_plant?<?php echo $query_string;?>" id="editplant" novalidate>
    <h2> Edit Existing Plant </h2>

      <div class="feedback <?php echo $name_feedback_class; ?>">Please enter the plant's name.</div>
      <div class="form_element">
        <label for="name_input">Plant Name:</label>
        <input type="text" id="name_input" name="name" value="<?php echo htmlspecialchars($name)?>"/>
      </div>
      <div class="feedback <?php echo $sci_name_feedback_class; ?>">Please enter the plant's scientific name.</div>
      <div class="feedback <?php echo $sci_name_feedback_unique; ?>">A plant with this scientific name already exists. Please enter a different scientific name.</div>
      <div class="form_element">
        <label for="sci_name_input">Scientific Name:</label>
        <input type="text" id="sci_name_input" name="sci_name" value="<?php echo htmlspecialchars($sci_name)?>"/>
      </div>
      <div class="feedback <?php echo $pp_id_feedback_class; ?>">Please enter the Plant ID.</div>
      <div class="feedback <?php echo $pp_id_feedback_unique; ?>">A plant with this Plant ID already exists. Please enter a different Plant ID.</div>
      <div class="form_element">
        <label for="plant_id_input">Plant ID:</label>
        <input type="text" id="plant_id_input" name="plant_id" value="<?php echo htmlspecialchars($plant)?>"/>
      </div>
      <div class="form_element">
      <img src = "/public/uploads/plants/<?php echo htmlspecialchars($pp_id)?>.jpg" onerror="this.onerror=null; this.src='/public/temp_plant.jpg'" alt="Image of "<?php echo htmlspecialchars($name);?>>
        Upload Image
      </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_constructive_box" name="is_exploratory_constructive" <?php echo htmlspecialchars($exploratory_constructive)?>/>
          <label for="is_exploratory_constructive_box">Exploratory Constructive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_exploratory_sensory_box" name="is_exploratory_sensory" <?php echo htmlspecialchars($exploratory_sensory)?>/>
          <label for="is_exploratory_sensory_box">Exploratory Sensory Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_physical_box" name="is_physical" <?php echo htmlspecialchars($physical)?>/>
          <label for="is_physical_box">Physical Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_imaginative_box" name="is_imaginative" <?php echo htmlspecialchars($imaginative)?> />
          <label for="is_imaginative_box">Imaginative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_restorative_box" name="is_restorative" <?php echo htmlspecialchars($restorative)?> />
          <label for="is_restorative_box">Restorative Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_expressive_box" name="is_expressive" <?php echo htmlspecialchars($expressive)?>/>
          <label for="is_expressive_box">Expressive Play</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_play_with_rules_box" name="is_play_with_rules" <?php echo htmlspecialchars($play_with_rules)?> />
          <label for="is_play_with_rules_box">Play with Rules</label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="is_bio_box" name="is_bio" <?php echo htmlspecialchars($bio)?>/>
          <label for="is_bio_box">Bio Play</label>
        </div>
        <h3> Tags </h3>
        <div class="form_element">
          <label for="classification">General Classification:</label>
          <select id="classification" name="class">
            <option value="" > </option>
            <option value="6" <?php echo htmlspecialchars($is_shrub);?>> Shrub </option>
            <option value="7" <?php echo htmlspecialchars($is_grass);?>> Grass </option>
            <option value="8" <?php echo htmlspecialchars($is_vine);?>> Vine </option>
            <option value="9" <?php echo htmlspecialchars($is_tree);?> > Tree </option>
            <option value="10" <?php echo htmlspecialchars($is_flower);?> > Flower </option>
            <option value="11" <?php echo htmlspecialchars($is_groundcover);?>> Groundcover </option>
            <option value="12" <?php echo htmlspecialchars($is_other);?>> Other </option>
          </select>
        </div>
        <div class="form_element">
          <label for="growth">Perennial/Annual:</label>
          <select id="growth" name="growth">
            <option value=""> </option>
            <option value="1" <?php echo htmlspecialchars($is_perennial);?>> Perennial</option>
            <option value="2" <?php echo htmlspecialchars($is_annual);?>> Annual</option>
          </select>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullsun" name="fullsun" value="3" <?php echo htmlspecialchars($is_fullsun);?> />
          <label for="fullsun">Full Sun </label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="partialshade" name="partialshade" value="4"<?php echo htmlspecialchars($is_partialshade);?>/>
          <label for="partialshade">Partial Shade </label>
        </div>
        <div class="form_element">
          <input type="checkbox" id="fullshade" name="fullshade" value="5"<?php echo htmlspecialchars($is_fullshade);?>/>
          <label for="fullshade">Full Shade </label>
        </div>
        <div class="form_element">
          <label for="hardiness">Hardiness Level</label>
          <input type="text" id="hardiness" name="hardiness" value="<?php echo htmlspecialchars($hardiness)?>"/>
        </div>

      </div>

      <div class="align-right">
        <input type="submit" value="Save Changes" name="edit_plant_submit"/>
    </div>

    </form>
      </main>
</body>
</html>
