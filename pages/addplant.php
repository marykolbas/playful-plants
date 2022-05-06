<?php
if(is_user_logged_in() && $is_admin){
  define("MAX_FILE_FIZE", 1000000);

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
  $img_feedback_class = 'hidden';

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
    $sticky_shrub = '';
    $sticky_grass = '';
    $sticky_vine = '';
    $sticky_tree = '';
    $sticky_flower = '';
    $sticky_groundcover = '';
    $sticky_other = '';
    $sticky_perennial= '';
    $sticky_annual= '';
    $sticky_fullsun ='';
    $sticky_partialshade = '';
    $sticky_fullshade = '';
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
  $classification = $_POST['class']; //will return the value (id) of that tag
  $season = $_POST['season'];
  $fullsun = $_POST['fullsun'];
  $partialshade = $_POST['partialshade'];
  $fullshade = $_POST['fullshade'];
  $tags_array = array_filter(array($classification, $season, $fullsun, $partialshade, $fullshade));

  $form_valid = True;

  $upload = $_FILES['img_file'];
  //IF THE SIZE OF THIS IS 0 THEN THEY DIDN'T UPLOAD AND U SHOULD JUST UPLOAD THE DEFAULT OR SOMETHING IDK I WILL COME BACK TO THIS

  // if file upload was successful
  if($upload['error']==UPLOAD_ERR_OK){
    $upload_filename = basename($upload['name']);
    // if($_FILES['size']>0){
    //   $upload_filename = $plant_id;
    // }
    // else{
    //   $upload_filename = 'temp_plant';
    // }
    $upload_ext = strtolower(pathinfo($upload_filename, PATHINFO_EXTENSION));
    if(!in_array($upload_ext, array('jpg'))&&!in_array($upload_ext, array('png'))&&!in_array($upload_ext, array('jpeg'))&&!in_array($upload_ext, array('gif'))){
      $form_valid=False;
    }
  }
  else{
    $form_valid=False;
  }


 $db->beginTransaction();

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
        ':ex_sen' => $exploratory_sensory>0 == 'checked' ? '1' : '0',
        ':phys' => $physical == 'checked' ? '1' : '0',
        ':imag' => $imaginative == 'checked' ? '1' : '0',
        ':rest' => $restorative == 'checked' ? '1' : '0',
        ':expr' => $expressive == 'checked' ? '1' : '0',
        ':rules' => $play_with_rules == 'checked' ? '1' : '0',
        ':bio' => $bio == 'checked' ? '1' : '0',
        ':hardiness' => $hardiness
      )
    );


    //last inserted id (new way)
    $plant_id = $db->lastInsertId('id');

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
      "INSERT INTO documents (id, file_name, file_ext) VALUES (:id, :file_name, :file_ext)",
      array(
        ':id' => $plant_id,
        ':file_name' => $plant_id, //$upload_filename,
        ':file_ext' => $upload_ext
      )
      );

    if($result && $result_file){ //use to do confirmation message?
      $result_inserted=True;

      //for the documents table
      //$record_id = $db->lastInsertId('id');
      $id_filename = "public/uploads/documents/" . $plant_id . "." . $upload_ext;
      move_uploaded_file($upload['tmp_name'], $id_filename);
    }
    $query_string = http_build_query(array(
        'pp_id' => $pp_id
      ));
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
  $db->commit();
}
} //close if statement for admin
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
<?php if(is_user_logged_in() && $is_admin){?>
  <h1>Playful Plants Project</h1>
  <?php if(is_user_logged_in()){?>
      <!-- <div class="align-right"> -->
        <ul class="nav_bar">
          <li><a href="/">Consumer View</a></li>
          <li><a href="/admin">Admin View</a></li>
          <li><a href="<?php echo logout_url();?>">Logout</a></li>
        </ul>
      <!-- </div> -->
    <?php } else{?>
        <!-- <div class="align-right"> -->
          <a href="/login"> Log-in </a>
        <!-- </div> -->
  <?php }?>

  <!--ADD PLANT FORM-->
  <form method="post" action="/addplant" id="addform" enctype = "multipart/form-data" novalidate>
      <h2> Add a New Plant </h2>
      <div class="confirmation">
        <?php
        if($result_inserted){?>
          <a class="green_link" href="/admin_plant?<?php echo $query_string;?>">
          <?php
          echo htmlspecialchars("Plant with Plant ID '". $pp_id);?></a>' was successfully added to the database.
        <?php }
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
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
        <div class = "form_element">
          <div class="feedback <?php echo $img_feedback_class; ?>">Please re-upload an image that is in jpg, png, jpeg, or gif format.</div>
          <label for="file">Upload Image: </label>
          <input type = "file" accept=".jpg, .png, .jpeg, .gif" id="file" name="img_file" />
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
              <option value="6" <?php echo htmlspecialchars($sticky_shrub)?>> Shrub </option>
              <option value="7" <?php echo htmlspecialchars($sticky_grass)?>> Grass </option>
              <option value="8" <?php echo htmlspecialchars($sticky_vine)?>> Vine </option>
              <option value="9" <?php echo htmlspecialchars($sticky_tree)?>> Tree </option>
              <option value="10" <?php echo htmlspecialchars($sticky_flower)?>> Flower </option>
              <option value="11" <?php echo htmlspecialchars($sticky_groundcover)?>> Groundcover </option>
              <option value="12" <?php echo htmlspecialchars($sticky_other)?>> Other </option>
            </select>
          </div>
          <div class="form_element">
            <label for="season">Perennial/Annual:</label>
            <select id="season" name="season">
              <option value="">None</option>
              <option value="1" <?php echo htmlspecialchars($sticky_perennial)?>> Perennial</option>
              <option value="2" <?php echo htmlspecialchars($sticky_annual)?>> Annual</option>
            </select>
          </div>
          <div class="form_element">
            <input type="checkbox" id="fullsun" name="fullsun" value="3" <?php echo htmlspecialchars($sticky_fullsun)?>/>
            <label for="fullsun" >Full Sun </label>
          </div>
          <div class="form_element">
            <input type="checkbox" id="partialshade" name="partialshade" value="4" <?php echo htmlspecialchars($sticky_partialshade)?>/>
            <label for="partialshade">Partial Shade </label>
          </div>
          <div class="form_element">
            <input type="checkbox" id="fullshade" name="fullshade" value="5" <?php echo htmlspecialchars($sticky_fullshade)?>/>
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
  <?php } else {?>
      <h1> Page Not Found </h1>
      <p>Oops! This page does not exist. <a href="/"> Click here to return to the catalog.</a></p>
    <?php } ?>
    </main>
</body>
</html>
