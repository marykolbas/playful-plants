<?php

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

//Set to empty string (so that the foreach conditions work)
// $is_shrub='';
// $is_grass = '';
// $is_vine='';
// $is_tree = '';
// $is_flower = '';
// $is_groundcover = '';
// $is_other = '';
// $is_perennial = '';
// $is_annual = '';
// $is_fullsun = '';
// $is_partialshade = '';
// $is_fullshade = '';

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
$img_feedback_class = 'hidden';

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
  $hardiness = trim($_POST['hardiness']);

  $classification = $_POST['class']; //will return the value (id) of that tag
  $growth = $_POST['growth'];
  // $fullsun = $_POST['fullsun'];
  // $partialshade = $_POST['partialshade'];
  // $fullshade = $_POST['fullshade'];
  $tags_array = array_filter(array($classification, $growth, $fullsun, $partialshade, $fullshade));

  $is_shrub=($classification==6 ? 'selected' : '');
  $is_grass = ($classification==7 ? 'selected' : '');
  $is_vine=($classification==8? 'selected' : '');
  $is_tree = ($classification==9 ? 'selected' : '');
  $is_flower = ($classification==10 ? 'selected' : '');
  $is_groundcover = ($classification==11 ? 'selected' : '');
  $is_other = ($classification==12 ? 'selected' : '');
  $is_perennial = ($growth==1 ? 'selected' : '');
  $is_annual = ($growth==2 ? 'selected' : '');
  $is_fullsun = ($_POST['fullsun']==3 ? 'checked' : '');
  $is_partialshade = ($_POST['partialshade']==4 ? 'checked' : '');
  $is_fullshade = ($_POST['fullshade']==5 ? 'checked' : '');

  //sticky for when form not valid, but you changed something so ^ won't remember it
  // $sticky_name=$trim($_POST['name']); // untrusted
  // $sticky_sci_name = trim($_POST['sci_name']);
  // $sticky_pp_id = strtoupper(trim($_POST['plant_id']));
  //   $sticky_exploratory_constructive = (isset($_POST['is_exploratory_constructive']) ? 'checked' : '');
  //   $sticky_exploratory_sensory = (isset($_POST['is_exploratory_sensory']) ? 'checked' : '');
  //   $sticky_physical = $physical;
  //   $sticky_imaginative = $imaginative;
  //   $sticky_restorative = $restorative;
  //   $sticky_expressive = $expressive;
  //   $sticky_play_with_rules = $play_with_rules;
  //   $sticky_bio = $bio;
  //   $sticky_shrub = ($classification==6 ? 'selected' : '');
  //   $sticky_grass = ($classification==7 ? 'selected' : '');
  //   $sticky_vine = ($classification==8 ? 'selected' : '');
  //   $sticky_tree = ($classification==9 ? 'selected' : '');
  //   $sticky_flower = ($classification==10 ? 'selected' : '');
  //   $sticky_groundcover = ($classification==11 ? 'selected' : '');
  //   $sticky_other = ($classification==12 ? 'selected' : '');
  //   $sticky_perennial= ($season==1 ? 'selected' : '');
  //   $sticky_annual= ($season==2 ? 'selected' : '');
  //   $sticky_fullsun = ($fullsun==3 ? 'checked' : '');
  //   $sticky_partialshade = ($partialshade==4 ? 'checked' : '');
  //   $sticky_fullshade = ($fullshade==5 ? 'checked' : '');




  $form_valid = True;

    $upload = $_FILES['img_file'];
    //IF THE SIZE OF THIS IS 0 THEN THEY DIDN'T UPLOAD AND U SHOULD JUST UPLOAD THE DEFAULT OR SOMETHING IDK I WILL COME BACK TO THIS

  // if file upload was successful
  if($upload['error']==UPLOAD_ERR_OK){
    $upload_filename = basename($upload['name']);
    $upload_ext = strtolower(pathinfo($upload_filename, PATHINFO_EXTENSION));
    if(!in_array($upload_ext, array('jpg'))){
      $form_valid=False;
    }
  }
  else{
    $form_valid=False;
  }


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
      "SELECT * FROM plants WHERE (sci_name = :sci_name) AND (id<>:id);",
      array(
        ':sci_name' => $sci_name,
        ':id' => $plant_id
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
  else{ //check if pp_id is unique
    $records = exec_sql_query(
      $db,
      "SELECT * FROM plants WHERE (id = :plant_id) AND (id<>:id);",
      array(
        ':plant_id' => $pp_id,
        ':id' => $plant_id
      )
    )-> fetchAll();
    if(count($records)>0){
      $form_valid = False;
      $pp_id_feedback_unique = True;
    }
  }


  //as 1 or 0 to use for update array
  $ex_con_up = $exploratory_constructive == 'checked' ? 1 : 0;
  $ex_sen_up = ($exploratory_sensory == 'checked' ? 1 : 0);
  $phys_up = ($physical == 'checked' ? 1 : 0);
  $imag_up = ($imaginative == 'checked' ? 1 : 0);
  $rest_up = ($restorative == 'checked' ? 1 : 0);
  $expr_up = ($expressive == 'checked' ? 1 : 0);
  $rules_up = $play_with_rules == 'checked' ? 1 : 0;
  $bio_up = ($bio == 'checked' ? 1 : 0);

  $plant_id_asint = (int)$plant_id;
  if($form_valid){
    //not done yet
    $result_update = exec_sql_query(
      $db,
      "UPDATE plants SET
        id=:plant_id,
        name = :name,
        sci_name = :sci_name,
        pp_id =:pp_id,
        exploratory_constructive = :ex_con,
        exploratory_sensory=:ex_sen,
        physical=:phys,
        imaginative=:imag,
        restorative=:rest,
        expressive=:expr,
        play_with_rules=:rules,
        bio=:bio,
        hardiness_level=:hardiness
        WHERE(id=:plant_id);",
      array(
        ':plant_id' => $plant_id_asint,
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
        ':hardiness' => $hardiness
      )
    );

    //$plant_id = $db->lastInsertId('id');

    //delete all entrytags associated with this plant
    $delete_old = exec_sql_query(
      $db,
      "DELETE FROM entry_tags WHERE(plant_id=:plant_id)",
      array(
        ':plant_id' => $plant_id
      )
    );
    //re-add new tags
    foreach($tags_array as $tag){
      $result_tags = exec_sql_query(
        $db,
        "INSERT INTO entry_tags (plant_id, tag_id) VALUES (:plant_id, :tag_id);",
        array(
          ':plant_id' => $plant_id,
          ':tag_id' => $tag
        )
      );
    }

    $result_file = exec_sql_query(
      $db,
      "UPDATE documents SET file_name =:file_name, file_ext=:file_ext WHERE (id=:id);",
      array(
        ':file_name' => $plant_id, //$upload_filename,
        ':file_ext' => $upload_ext,
        ':id' => $plant_id
      )
      );

    if($result_update && $result_file){ //use to do confirmation message
      $result_edited=True;

      $id_filename = "public/uploads/documents/" . $plant_id . "." . $upload_ext;
      move_uploaded_file($upload['tmp_name'], $id_filename);

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

    $sticky_shrub = ($classification==6 ? 'selected' : '');
    $sticky_grass = ($classification==7 ? 'selected' : '');
    $sticky_vine = ($classification==8 ? 'selected' : '');
    $sticky_tree = ($classification==9 ? 'selected' : '');
    $sticky_flower = ($classification==10 ? 'selected' : '');
    $sticky_groundcover = ($classification==11 ? 'selected' : '');
    $sticky_other = ($classification==12 ? 'selected' : '');
    $sticky_perennial= ($season==1 ? 'selected' : '');
    $sticky_annual= ($season==2 ? 'selected' : '');
    $sticky_fullsun = ($fullsun==3 ? 'checked' : '');
    $sticky_partialshade = ($partialshade==4 ? 'checked' : '');
    $sticky_fullshade = ($fullshade==5 ? 'checked' : '');

    $img_feedback_class = '';
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
      <?php if(is_user_logged_in()){?>
        <a href="/admin">Return to Admin View</a>
    </div>
    <div class="align-right">
        <a href=<?php echo logout_url();?>>Logout</a>
      <?php } else{ ?>
        <a href="/login"> Log-in </a>
      <?php }?>
    </div>
    <?php
    $query_string = http_build_query(array(
        'pp_id' => $plant
      ));
    ?>
    <div class="confirmation">
      <?php
      if($result_edited){
        echo htmlspecialchars("Plant with Plant ID '" . $pp_id . "' was successfully edited.");?>
      <?php } ?>

    </div>
    <div class="columns">
      <a href="/"> Return to Consumer Catalog </a>
      <a href="/admin"> Return to Admin View </a>
    </div>
    <form method="post" action="/admin_plant?<?php echo $query_string;?>" id="editplant" enctype = "multipart/form-data" novalidate>
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
      <?php
            $result_documentstable = exec_sql_query(
            $db,
            "SELECT file_name AS 'documents.file_name' FROM documents WHERE (id=:plant_id);",
            array(
            ':plant_id' => $plant_id
            )
        )->fetchAll();
        ?>
      <img src="/public/uploads/documents/<?php echo htmlspecialchars($result_documentstable[0]['documents.file_name']);?>.jpg" alt='"Image of "<?php echo htmlspecialchars($name);?>'>
      <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
      <div class = "form_element">
        <div class="feedback <?php echo $img_feedback_class; ?>">Please re-upload an image that is in jpg format.</div>
        <label for="file">Upload New Image: </label>
        <input type = "file" id="file" accept=".jpg" name="img_file" />
      </div>
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
            <!-- <option value="" > </option> -->
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
            <!-- <option value=""> </option> -->
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


      <div class="align-right">
        <input type="submit" value="Save Changes" name="edit_plant_submit"/>
    </div>

    </form>
      </main>
</body>
</html>
