<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/script.js"></script>
</head> 
<body>
    

<?php
    // connection parameters
    include 'header.php';
    include 'corevalues.php';
    include 'functions.php';    
?>
    
<form action="horseracing.php" method="post">
    <input type="submit" name="start" value="Create race" />
</form>
    
<form action="horseracing.php" method="post">
    <input type="submit" name="progress" id="progress" value="Progress" />
</form>
    
    <!--- left side tables -->
    <div class="races_left">
        <div class="races_title">Current Races</div>
    <?php
        
    // select current $running races
    $race = mysqli_query ($conn, "SELECT * FROM race WHERE race_end < 1 ORDER BY race_id DESC LIMIT $running");
    
    if (empty($race) === FALSE){ 
        // printing the current races IF THERE ARE RACES RUNNING
        while ($row_race = mysqli_fetch_array($race)) {
            // creating the table
            echo '<div class="races_container">
                    <div class="race_name">Race n°'.$row_race['race_id'].'
                    </div>
                    <div class="race_labels">
                        <span class="label_horse">'.$label_name.'</span>
                        <span class="label_end">'.$label_end.'</span>
                        <span class="label_spd">'.$label_spd.'</span>
                        <span class="label_str">'.$label_str.'</span>
                        <span class="label_time">'.$label_time.'</span>
                        <span class="label_pos">'.$label_pos.'</span>
                        <span class="label_dist">'.$label_dist.'</span>
                    </div>';
            
                // selecting the horses in the race           
                $runners = mysqli_query ($conn, "SELECT horse.*, race.*, clghorserace.* FROM horse JOIN clghorserace on horse_id = clg_horse JOIN race ON race_id = clg_race WHERE race_id = $row_race[race_id]");
                      
                $h = 1;
                // printing the entries of the horses in the race
                while (($h <= $horsenum) && ($row_runners = mysqli_fetch_array($runners))) {
                
                // printing a green line if the horse has finished
                    if ($row_runners['finish'] > 0) {
                        echo '<div class="race_horse race_finished">';
                    } else 
                    {
                        echo '<div class="race_horse">';
                    } 
                    // continue printing the table
                    echo '
                        <span class="label_horse">Horse n°'.$row_runners['horse_id'].'</span>
                        <span class="label_end">'.$row_runners['endurance'].'</span>
                        <span class="label_spd">'.($row_runners['speed']+$basespd).'</span>
                        <span class="label_str">'.$row_runners['strength'].'</span>
                        <span class="label_time">'.$row_runners['horse_time'].'</span>
                        <span class="label_pos">'.$row_runners['horse_pos'].'</span>
                        <span class="label_dist">'.$row_runners['horse_dist'].'</span>
                    </div>';
                    
                $h = $h+1;
            }
            mysqli_free_result($runners);
            
            echo '</div>';  
            
        } 
    } // if 
      else {
      }  
         mysqli_free_result($race);
    ?>
        
    </div>
    <!-- end left side - start right side -->
    <div class="races_right">
        <!-- best run -->
        <div class="races_title">Best run</div>
        <?php 
            // search the horse which has finished the race with the lowest time
                $besthorse = mysqli_query ($conn, "SELECT * FROM horse WHERE finish > 0 ORDER BY horse_time ASC LIMIT 1");
                $row_best = mysqli_fetch_array($besthorse); 
            
            echo 
            '<div class="races_bestrun">
            <div class="race_labels">
               <span class="label_horse">'.$label_name.'</span>
                <span class="label_end">'.$label_end.'</span>
                <span class="label_spd">'.$label_spd.'</span>
                <span class="label_str">'.$label_str.'</span>
                <span class="label_time">'.$label_time.'</span>
            </div>
            <div class="race_horse">
               <span class="label_horse">Horse n°'.$row_best['horse_id'].'</span>
                <span class="label_end">'.$row_best['endurance'].'</span>
                <span class="label_spd">'.($row_best['speed']+$basespd).'</span>
                <span class="label_str">'.$row_best['strength'].'</span>
                <span class="label_time">'.$row_best['horse_time'].'</span>
            </div>
        </div>  ';
        mysqli_free_result($besthorse);
        ?>
        
        <!-- last X races -->
        <div class="races_title">Last <?php echo $lastraces;?> races results</div>
        <?php 
            // select last races
            $race = mysqli_query ($conn, "SELECT * FROM race WHERE race_end > 0 ORDER BY race_id DESC LIMIT $lastraces");
        
            // printing 
            while ($row_race = mysqli_fetch_array($race)) {
            // creating the table
                echo '<div class="races_last">
                        <div class="race_name">Race n°'.$row_race['race_id'].'</div>
                        <div class="race_labels">
                            <span class="label_horse">'.$label_name.'</span>
                            <span class="label_pos">'.$label_pos.'</span>
                            <span class="label_time">'.$label_time.'</span>
                        </div>';
                        // selecting the top Y horses in the race           
                        $runners = mysqli_query ($conn, "SELECT horse_id, horse_time, horse_pos, race.race_id, clghorserace.* FROM horse JOIN clghorserace on horse_id = clg_horse JOIN race ON race_id = clg_race WHERE race_id = $row_race[race_id] ORDER BY horse_pos ASC LIMIT $tophorses");
                
                        // printing the horses
                        while ($row_runners = mysqli_fetch_array($runners)){
                    echo '<div class="race_horse">
                            <span class="label_horse">Horse n°'.$row_runners['horse_id'].'</span>
                            <span class="label_pos">'.$row_runners['horse_pos'].'</span>
                            <span class="label_time">'.$row_runners['horse_time'].'</span>
                        </div>';
                    }
                echo '</div>';
            }
        ?>
    </div>
    <!-- closing right side -->
<?php

    // closing connection
    include 'footer.php';
?>

</body>
</html>
