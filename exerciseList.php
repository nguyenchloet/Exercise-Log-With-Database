<?php
    class ExerciseList {
        /**
        * Constructor
        */
        public function __construct(){     
            $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
        }
        /**
        * Output exercises
        */
        public function show() {
            include 'connect.php';

            $content = '';
            $addAnExercisePrompt = '';

            //check if there are exercises for current day
            $getExercises = "SELECT COUNT(*) FROM exercise";

            //create a new element to hold exercise list output
            $table = '';

            if ($result = $pdo->query($getExercises)) {
                if ($result->fetchColumn() > 0) {
                    $getExercises = "SELECT id, exerciseName, lbWeight, kgWeight, sets, reps, difficulty, completed FROM exercise";
                    foreach ($pdo->query($getExercises) as $row) {
                        $id =  $row['id'];

                        // convert uniqid() to timestamp without microsecond precision
                        $timestamp = substr($id, 0, -5);
                        //echo date('r', hexdec($timestamp));
                        $datestamp = substr($timestamp, 0, 8);
                        //echo " datestamp is: $datestamp ";
                        //echo date('M j, Y', hexdec($datestamp));
                        $exerciseDate = date('M j, Y', hexdec($datestamp));
                        //echo $exerciseDate;
                        // get current day to compare against
                        $currentDate = date("M j, Y");   
                        // echo $currentDate;

                        // if exercise date is found for current date, output column

                        if ($currentDate == $exerciseDate) {
                            $ename = $row['exerciseName'];
                            $lbWeight = $row['lbWeight'];
                            $kgWeight = $row['kgWeight'];
                            $sets = $row['sets'];
                            $reps = $row['reps'];
                            $difficulty = $row['difficulty'];
                            $completed = $row['completed'];
                            //echo "CURRENT: $exerciseDate $ename";

                            $content='<div id="exercise-output">'.
                            '<div class="column-data data-output-row row">'.
                                '<div class="exercise-name-data data-header">'.$ename.'</div>'.
                                '<div class="weight-output data-header">'.$lbWeight.' / '.$kgWeight.'</div>'.
                                '<div class="sets-output data-header">'.$sets.'x'.'</div>'.
                                '<div class="reps-output data-header">'.$reps.'</div>'.
                                '<div class="difficulty-output data-header"><input type="number" class="difficulty-input" name="difficulty" min="0" max="10" step="1">'.$difficulty.'</input></div>'.
                                '<div class="completed-output data-header"><input type="checkbox">'.$completed.'</input></div>'.
                                '<form action="deleteExercise.php" method="POST">'.
                                    '<button name="delete-exercise" class="fa fa-trash-o delete-button data-header" value="<?php=$id;?>"></button>'.
                                '</form>'.
                            '</div>'.
                            $content.='</div>'; 
                        }  
                    }
                    $content=
                    '<div class="column-headers exercise-output-row row">'.
                        '<div class="exercise-header column-header"><strong>Exercise</strong></div>'.
                        '<div class="weight-header column-header"><strong># / kg</strong></div>'.
                        '<div class="reps-header column-header"><strong>Sets</strong></div>'.
                        '<div class="sets-header column-header"><strong>Reps</strong></div>'.
                        '<div class="difficulty-header column-header"><strong>Difficulty</strong></div>'.
                        '<div class="completed-header column-header"><strong>Completed</strong></div>'.
                        '<div class="alignment column-header"></div>'.
                    '</div>'.
                    $content.='</div>';
                    return $content;

                } else {
                    return $addAnExercisePrompt = "Nothing yet for today. Add an exercise?";
                } 
            }
        }
        public function deleteExercise($id)
        {
           
        }
    }
?>