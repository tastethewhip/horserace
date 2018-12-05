 <?php
    // stats randomized value 
    $statmin = 0;
    $statmax = 100; //it will be /10
    // number of horses per race
    $horsenum = 8;
    // horses' base speed 
    $basespd = 5;
    // jockey's effect
    $jockey = 5;
    // race distance
    $racemt = 1500;
    // race running limit
    $running = 3;
    // total horses currently in active races 
    $totalhorses = $running*$horsenum;

    // seconds of progress in the button
    $progressvalue = 10;
    // actual progress value cause it creates a refresh 
    $progress = $progressvalue-1;

    // top X for last Y races results
    $tophorses = 3;
    $lastraces = 5;

    // table labels
    $label_spd = "Speed [m/s]";
    $label_time = "Time [s]";
    $label_dist = "Distance [m]";
    $label_name = "Horse";
    $label_end = "Endurance";
    $label_str = "Strength";
    $label_pos = "Position";

?>