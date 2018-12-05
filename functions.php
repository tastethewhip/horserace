<?php        
 ///***** CREATING RACE AND HORSES ****/

  if (isset($_POST['start'])) {

        
        // create the race
        mysqli_query ($conn, "INSERT INTO race (distance, race_end) VALUES ($racemt,0");
            
        $i = 1;
        // counting the number of horses needed
        while ($i <= $horsenum) {
        
            // generating the horses' stats
            $stats = array(rand($statmin,$statmax)/10, rand($statmin,$statmax)/10, rand($statmin,$statmax)/10);
            
            // inserting the horses' stats in the db
            mysqli_query ($conn, "INSERT INTO horse (endurance,speed,strength,horse_time,horse_dist) VALUES ($stats[0],$stats[1],$stats[2],0,$racemt)"); 
            
            // creating link between horses and race they are in 
            mysqli_query ($conn, "INSERT INTO clghorserace (clg_race,clg_horse) VALUES ((SELECT race_id FROM race ORDER BY race_id DESC LIMIT 1),(SELECT horse_id FROM horse ORDER BY horse_id DESC LIMIT 1))");
            
            $i = $i+1;
       }
 }
            
//*** FUNCTION FOR 1 SEC OF ADVANCEMENT OF THE RACE ***//

        function advancement () {
            $conn = db();
            include 'corevalues.php';
                
            /** MAKE TIME PASS AND MODIFY VALUES */
            /* selecting all the horses that are not done */
            $runtime = mysqli_query ($conn, "SELECT horse.*, race.*, clghorserace.* FROM race JOIN clghorserace ON race_id = clg_race JOIN horse ON horse_id = clg_horse WHERE finish = 0"); 
         
                  while ($row_runtime = mysqli_fetch_array($runtime)) {
                    
                        // get stats needed
                        $mtendurance = $row_runtime['endurance']*100;
                        $bestspeed = $row_runtime['speed']+5;
                        $slow = $jockey - (($jockey * $row_runtime['strength'] *8)/100);
                        $worstspeed = $bestspeed - $slow;
                        $bestspeedmt = $racemt - $mtendurance;
                      
                        //if distance >= distance - endurance, better speed update
                        if ($row_runtime['horse_dist'] >= $bestspeedmt) {
                            mysqli_query ($conn, "UPDATE horse SET horse_dist = horse_dist - $bestspeed WHERE horse_id = $row_runtime[horse_id]");
                        } 
                        //if distance < distance - endurance, worse speed update
                        else if ($row_runtime['horse_dist'] < $bestspeedmt)
                        {
                        //check if the distance is higher than the speed; if yes, subtract, if no, insert 0 and determine that the horse finished the race
                                if ($row_runtime['horse_dist'] > $worstspeed){
                                    mysqli_query ($conn, "UPDATE horse SET horse_dist = horse_dist - $worstspeed WHERE horse_id = $row_runtime[horse_id]");
                                } 
                                else if ($row_runtime['horse_dist'] <= $worstspeed)
                                {
                                    mysqli_query ($conn, "UPDATE horse SET horse_dist = 0 WHERE horse_id = $row_runtime[horse_id]");
                                    mysqli_query ($conn, "UPDATE horse SET finish = 1 WHERE horse_id = $row_runtime[horse_id]");
                                    
                                }// if else
                        }//if else
                        
                        //if there is still distance to run, update
                        if ($row_runtime['horse_dist'] > 0) {
                            mysqli_query ($conn, "UPDATE horse SET horse_time = horse_time + 1 WHERE horse_id = $row_runtime[horse_id]");
                        } 
                            
                } // while
                
                mysqli_free_result($runtime);
                
                // GIVE THE HORSES A POSITION
                        // get the active races
                        $act_races = mysqli_query ($conn, "SELECT *  FROM race ORDER BY race_id DESC LIMIT 1");  //change limit 1 with the max active races //
            
                        // get each race
                         while ($row_act = mysqli_fetch_array($act_races)) {
                             $distances = mysqli_query ($conn, "SELECT horse_id, horse_dist, horse_pos, finish, race_id, clghorserace.* FROM race JOIN clghorserace ON race_id = clg_race JOIN horse ON horse_id = clg_horse WHERE race_id = $row_act[race_id] ORDER BY horse_dist ASC"); 
                             
                             // positions start from 1
                            $pos = 1;
                             
                             // loop to give everyone - from top distance to bot - a position
                            while (($pos <= $horsenum) && ($row_dist = mysqli_fetch_array($distances))) {
                                
                                // if the horse has arrived, the position doesn't change and the value just increase for the next horse to come
                                if ($row_dist['finish'] > 0){
                                $pos = $pos +1;
                                } else {
                                // otherwise it uploads
                                mysqli_query ($conn, "UPDATE horse SET horse_pos = $pos WHERE horse_id = $row_dist[horse_id]");
                                $pos = $pos +1;
                                }
                                
                            } 
                         }
        
        };

/**** CALL THE FUNCTION FOR 1 SEC OF ADVANCEMENT ****/

        advancement ();

//**** 10 SEC PROGRESSION ***/
            
       if (isset($_POST['progress'])) {
                $pg = 1;
                
                // repeat the function a "progress" amount of times
                while ($pg <= $progress) {
                    // call the function to advance by 1 sec 
                    advancement();
                    $pg++;
                } 
       }  
?>