<!-- todo: validate input -->
<!-- todo: set up lbWeight / kgWeight input so if one is changed, the other adjusts accordingly before saving to database -->
<!-- todo: check that weight reps and sets are not the same for duplicate exerciseName entry -->
<!-- todo: output exercise data if already exists for that day or when added by user -->
<!-- todo: update difficulty after exercise added and save to db -->
<!-- todo: toggle completed after exercise added and save to db -->
<!-- todo: if no exercises for that day, add output asking user to add exercise -->


<?php 
include 'connect.php';

$status = "";
$addAnExercisePrompt = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = uniqid();
  $exerciseName = $_POST['exerciseName'];
  $lbWeight = $_POST['lbWeight'];
  $lbsToKg = 0.4535;
  $kgWeight = $lbWeight * $lbsToKg;
  $sets = $_POST['sets'];
  $reps = $_POST['reps'];
  $difficulty = 0;
  $completed = 0;

  if(empty($exerciseName) || empty($lbWeight) || empty($sets) || empty($reps)) {
    $status = "All fields are required.";
  } else {
    if(strlen($exerciseName) >= 255 || !preg_match("/^[a-zA-Z-'\s]+$/", $exerciseName)) {
      $status = "Please enter a valid exercise";
    } else {
       // check if row already exists
       // TO DO: check if row already exists with that name ONLY FOR THAT DATE 
       $dupQuery = "SELECT COUNT(*) FROM exercise WHERE exerciseName='$exerciseName' ";
       if ($result = $pdo->query($dupQuery)) {
        if ($result->fetchColumn() > 0) {
            $dupQuery = "SELECT exerciseName FROM exercise WHERE exerciseName='$exerciseName'";
            foreach ($pdo->query($dupQuery) as $row) {
              echo "DUPLICATE SEARCH ==> Exercise: " .  $row['exerciseName'] . "\n";
            }
       } else {
          //echo "No duplicates found.";
          $sql = "INSERT INTO exercise (id, exerciseName, lbWeight, kgWeight, sets, reps, difficulty, completed) VALUES (:id, :exerciseName, :lbWeight, :kgWeight, :sets, :reps, :difficulty, :completed)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['id' => $id, 'exerciseName' => $exerciseName, 'lbWeight' => $lbWeight, 'kgWeight' => $kgWeight,'sets' => $sets,'reps' => $reps, 'difficulty' => $difficulty, 'completed' => $completed]);
         
          $status = "Submitted.";
          
          $id = "";
          $exerciseName = "";
          $lbWeight = "";
          $kgWeight = "";
          $sets = "";
          $reps = "";
          $difficulty = 0;
          $completed = 0;
        }
      }
    }
  }
  $result = null;
  $pdo = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Daily Exercise Log</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="form.css" type="text/css"/>
  <link rel="stylesheet" href="exerciseList.css" type="text/css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    <?php include "exerciseList.css"; include "form.css"?>
  </style>
</head>
<body>

  <div class="container">
    <div class="header"> 
      <h1 id="cal-header">Daily Workout Log</h1>
        <h2 id="cal-header"> 
          <!--  $today = date("D M j G:i:s T Y");  output: Sat Mar 10 17:16:18 MST 2001 -->
            <?php 
              // set the default timezone - 'UST' advantages?
              date_default_timezone_set('EST');

              $today = date("D M j, Y");               
              echo "$today";
            ?>
        </h2>
    </div>
    <!-- Exercise Form -->
    <div class="exercise-form">
      <form action="" method="POST" class="main-form">
        <div class="form-group">
          <label for="exerciseName">Exercise</label>
          <input type="text" name="exerciseName" id="exerciseName" class="exerciseName data-input" required>
        </div>

        <div class="form-group">
          <label for="lbWeight">Weight</label>
          <select name="weight-toggle" id="weight-toggle">
            <option value='lb'>lb</option>
            <option value='kg'>kg</option>
          </select>
          <input type="number" min="0" max="9999" step=".01" name="lbWeight" id="lbWeight" class="lbWeight data-input" placeholder="#">
        </div>

        <div class="form-group">
          <label for="sets">Sets</label>
          <input type="number" name="sets" min="1" max="50" id="sets" class="sets data-input" required>
        </div>

        <div class="form-group">
          <label for="reps">Reps</label>
          <input type="number" name="reps" min="1" max="100" id="reps" class="reps data-input" required>
        </div>
        
        <div class="form-action-group">
          <button type="submit" id="submit-button"><strong>+</strong></button>
        </div> 
      </form> 
      <div class="form-status">
        <?php echo $status ?>
      </div>
    </div> <!-- END EXERCISE INPUT FORM -->
   
    <div class="exercise-output">
        <?php 
          include 'exerciseList.php';
          echo $addAnExercisePrompt;
          //output exercises if found
          $exerciseList = new ExerciseList();
          echo $exerciseList->show();
        ?>

    </div>  <!-- END EXERCISE OUTPUT  -->
  </div> <!-- END CONTAINER -->
</body>
</html>