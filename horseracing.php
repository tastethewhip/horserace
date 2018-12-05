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
        $race = mysqli_query ($conn, "SELECT * FROM race ORDER BY race_id DESC LIMIT $running");
    
        // printing the current races
        while ($row_race = mysqli_fetch_array($race)) {
            // creating the table
            echo '<div class="races_container">
                    <div class="race_name">Race n°'.$row_race['race_id'].'
                    </div>
                    <div class="race_labels">
                        <span class="label_horse">Horse</span>
                        <span class="label_end">Endurance</span>
                        <span class="label_spd">Speed</span>
                        <span class="label_str">Strength</span>
                        <span class="label_time">Time</span>
                        <span class="label_pos">Position</span>
                        <span class="label_dist">Distance</span>
                    </div>';
            
                // selecting the horses in the race           
                $runners = mysqli_query ($conn, "SELECT horse.*, race.race_id, clghorserace.* FROM horse JOIN clghorserace on horse_id = clg_horse JOIN race ON race_id = clg_race WHERE race_id = $row_race[race_id]");
                      
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
                        <span class="label_time">'.$row_runners['horse_time'].' s</span>
                        <span class="label_pos">'.$row_runners['horse_pos'].'</span>
                        <span class="label_dist">'.$row_runners['horse_dist'].' m</span>
                    </div>';
                    
                $h = $h+1;
            }
            
            echo '</div>';  
            
        } 
         mysqli_free_result($race);
         mysqli_free_result($runners);
    ?>
        
    </div>
    <!-- end left side - start right side -->
    <div class="races_right">
        <!-- best run -->
        <div class="races_title">Best run</div>
        <div class="races_bestrun">
            <?php 
                // search the horse which has finished the race with the lowest time
                $besthorse = mysqli_query ($conn, "SELECT * FROM horse WHERE finish = 1 ORDER BY horse_time ASC LIMIT 1"); 
                $row_best = mysqli_fetch_array($besthorse);
            ?>
            <div class="race_labels">
               <span class="label_horse">Horse</span>
                <span class="label_end">Endurance</span>
                <span class="label_spd">Speed</span>
                <span class="label_str">Strength</span>
                <span class="label_time">Time</span>
            </div>
            <div class="race_horse">
               <span class="label_horse">Horse n°<?php echo $row_best['horse_id'];?></span>
                <span class="label_end"><?php echo $row_best['endurance'];?></span>
                <span class="label_spd"><?php echo ($row_best['speed']+$basespd);?></span>
                <span class="label_str"><?php echo $row_best['strength'];?></span>
                <span class="label_time"><?php echo $row_best['horse_time'].' s';?></span>
            </div>
        </div>   
        
        <!-- last X races -->
        <div class="races_title">Last <?php echo $lastraces;?> races results</div>
        <?php 
            // select last races
            $race = mysqli_query ($conn, "SELECT * FROM race ORDER BY race_id DESC LIMIT $lastraces");
        
            // printing 
            while ($row_race = mysqli_fetch_array($race)) {
            // creating the table
                echo '<div class="races_last">
                        <div class="race_name">Race n°'.$row_race['race_id'].'</div>
                        <div class="race_labels">
                            <span class="label_horse">Horse</span>
                            <span class="label_pos">Position</span>
                            <span class="label_time">Time</span>
                        </div>';
                        // selecting the top Y horses in the race           
                        $runners = mysqli_query ($conn, "SELECT horse_id, horse_time, horse_pos, race.race_id, clghorserace.* FROM horse JOIN clghorserace on horse_id = clg_horse JOIN race ON race_id = clg_race WHERE race_id = $row_race[race_id] ORDER BY horse_pos ASC LIMIT $tophorses");
                
                        // printing the horses
                        while ($row_runners = mysqli_fetch_array($runners)){
                    echo '<div class="race_horse">
                            <span class="label_horse">Horse n°'.$row_runners['horse_id'].'</span>
                            <span class="label_pos">'.$row_runners['horse_pos'].'</span>
                            <span class="label_time">'.$row_runners['horse_time'].' s</span>
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