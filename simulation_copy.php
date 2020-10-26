<?php

    include 'vendor/autoload.php';

    function boxMuller($n){
        $_C1 = [];
        $_C2 = [];
        $i = 0;
        while($n >  $i){
            $U1 = rand(0,999) / 1000;
            $U2 = rand(0,999) / 1000;
            $Z1 = sqrt(-2.0 * log($U1)) * cos(( 2.0 * pi() ) * $U2);
            $Z2 = sqrt(-2.0 * log($U1)) * sin(( 2.0 * pi() ) * $U2);
            array_push($_C1, $Z1);
            array_push($_C2, $Z2);
            $i++;
        }
        return [$_C1, $_C2];
    }

    $_V_1 = $_V_2 = $_V_3 = $_V_4 = array(); 
    $archivo = fopen("load_data/Var-logqs-asist.csv", "r");
    while (($datos = fgetcsv($archivo, ";")) == true) {
        $new_data = explode(";",$datos[0]);
        array_push($_V_1, floatval($new_data[1]));
        array_push($_V_2, floatval($new_data[2]));
        array_push($_V_3, floatval($new_data[3]));
        array_push($_V_4, floatval($new_data[4]));
    }fclose($archivo);  
    //logQS(PhD)
    $V_1 = new NumPHP\Core\NumArray($_V_1);
    //logQS(HU)
    $V_2 = new NumPHP\Core\NumArray($_V_2);
    //Hiring Year
    $V_3 = new NumPHP\Core\NumArray($_V_3);
    //Impact Factor @ Hiring Year
    $V_4 = new NumPHP\Core\NumArray($_V_4);
    //Lectura de clusters
    //Data_C1
    $_data_C1    = [];
    $_data_C2    = [];
    $_data_C3    = [];
    $_data_C4    = [];
    $archivo    = fopen("load_data/Cluster_log1.csv", "r");
    while (($datos = fgetcsv($archivo, ";")) == true) {
        $new_data = explode(";",$datos[0]);
        array_push($_data_C1, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
    }fclose($archivo);
    //Data_C2
    $archivo    = fopen("load_data/Cluster_log2.csv", "r");
    while (($datos = fgetcsv($archivo, ";")) == true) {
        $new_data = explode(";",$datos[0]);
        array_push($_data_C2, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
    }fclose($archivo);
    //Data_C3
    $Data_C3    = [];
    $archivo    = fopen("load_data/Cluster_log3.csv", "r");
    while (($datos = fgetcsv($archivo, ";")) == true) {
        $new_data = explode(";",$datos[0]);
        array_push($_data_C3, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
    }fclose($archivo);
    //Data_C4
    $archivo    = fopen("load_data/Cluster_log4.csv", "r");
    while (($datos = fgetcsv($archivo, ";")) == true) {
        $new_data = explode(";",$datos[0]);
        array_push($_data_C4, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
    }fclose($archivo);

    $Clust      =  [$_data_C1, $_data_C2, $_data_C3, $_data_C4] ;

    $Data_C1 = array_map(null, ...$_data_C1);
    $Data_C2 = array_map(null, ...$_data_C2);
    $Data_C3 = array_map(null, ...$_data_C3);
    $Data_C4 = array_map(null, ...$_data_C4);
        //Clust{1} Clust{2} Clust{3} Clust{4}
    //$Clust      =  [$Data_C1, $Data_C2, $Data_C3, $Data_C4] ;
    //X{1} X{2} X{3} X{4}
    $X = [];
    array_push($X, [array_sum($Data_C1[2])/count($Data_C1[2]),array_sum($Data_C1[3])/count($Data_C1[3]),array_sum($Data_C1[1])/count($Data_C1[1]),array_sum($Data_C1[4])/count($Data_C1[4])]);
    array_push($X, [array_sum($Data_C2[2])/count($Data_C2[2]),array_sum($Data_C2[3])/count($Data_C2[3]),array_sum($Data_C2[1])/count($Data_C2[1]),array_sum($Data_C2[4])/count($Data_C2[4])]);
    array_push($X, [array_sum($Data_C3[2])/count($Data_C3[2]),array_sum($Data_C3[3])/count($Data_C3[3]),array_sum($Data_C3[1])/count($Data_C3[1]),array_sum($Data_C3[4])/count($Data_C3[4])]);
    array_push($X, [array_sum($Data_C4[2])/count($Data_C4[2]),array_sum($Data_C4[3])/count($Data_C4[3]),array_sum($Data_C4[1])/count($Data_C4[1]),array_sum($Data_C4[4])/count($Data_C4[4])]);

    //$XY         = [$V_2, $V_3];
    //$AB         = [$V_1, $V_4];

    $XY = new NumPHP\Core\NumArray([$V_2, $V_3]);
    $AB = new NumPHP\Core\NumArray([$V_1, $V_4]);

    $QS_1    = 5;
    $logQS_1 = log10($QS_1);
    $IF_1    = 3;

    $AB_0 = new NumPHP\Core\NumArray([$logQS_1, $IF_1]);
    $M_CO = new NumPHP\Core\NumArray([$XY, $AB]);

    $CO = new NumPHP\Core\NumArray(
        [
            [ 0.3285,   0.3776,  0.1455,  -1.0649],
            [ 0.3776,   7.9001,  0.3820,  -0.4802],
            [ 0.1455,   0.3820,  0.5907,  -0.9224],
            [-1.0649,  -0.4802, -0.9224,  26.4672]
        ]
    );

    $CO_11      = [array($CO->getData()[0][0],$CO->getData()[0][1]), array($CO->getData()[1][0],$CO->getData()[1][1])];
    $CO_12      = [array($CO->getData()[0][2],$CO->getData()[0][3]), array($CO->getData()[1][2],$CO->getData()[1][3])];
    $CO_21      = [array($CO->getData()[2][0],$CO->getData()[2][1]), array($CO->getData()[3][0],$CO->getData()[3][1])];
    $CO_22      = [array($CO->getData()[2][2],$CO->getData()[2][3]), array($CO->getData()[3][2],$CO->getData()[3][3])];

    $XY_m       = [array_sum($V_2->getData())/count($V_2->getData()) , array_sum($V_3->getData())/count($V_3->getData())];
    $AB_m       = [array_sum($V_1->getData())/count($V_1->getData()) , array_sum($V_4->getData())/count($V_4->getData())];

    //ESP_multi = XY_m + (CO_12*inv(CO_22)*(AB_0 - AB_m)')';
    //$invCO_22   = [ [1.7903, 0.0624],[0.0624, 0.0400] ];
    
    $AB_0 = $AB_0->getData();     
    $AB_0_AB_m  = [ 
                    [ $AB_0[0] - $AB_m[0] ], 
                    [ $AB_0[1] - $AB_m[1] ]
                ];

    $CO_12_invCO_22 = [ [0.1940, -0.0335],
                        [0.6539, 0.0046]];

    $CO_12_invCO_22_AB_0_AB_m = [   [$CO_12_invCO_22[0][0]*$AB_0_AB_m[0][0]+$CO_12_invCO_22[0][1]*$AB_0_AB_m[1][0]],
                                    [$CO_12_invCO_22[1][0]*$AB_0_AB_m[0][0]+$CO_12_invCO_22[1][1]*$AB_0_AB_m[1][0]]];

    $CO_12_invCO_22_AB_0_AB_m = [$CO_12_invCO_22_AB_0_AB_m[0][0], $CO_12_invCO_22_AB_0_AB_m[1][0]];

    $ESP_multi  = [$XY_m[0]+$CO_12_invCO_22_AB_0_AB_m[0], $XY_m[1]+$CO_12_invCO_22_AB_0_AB_m[1]];

    //V_multi = CO_11 - CO_12*inv(CO_22)*CO_21;
    $V_multi    = [
                    [ 0.2646, 0.2874],
                    [ 0.2874, 7.6525]
                  ];

    /*  R = mvnrnd(ESP_multi,V_multi,1000);
        R = mvnrnd(mu,Sigma,n)
                  ESP_multi --> mu
                  V_multi   --> Sigma
                  n         --> 1000

    */
    use  NumPHP\LinAlg\LinAlg;

    $mu = new NumPHP\Core\NumArray($ESP_multi);
    $sigma = new NumPHP\Core\NumArray($V_multi);
    $sigma = LinAlg::cholesky($V_multi);
    $n = 1000;

    $new = new NumPHP\Core\NumArray(boxMuller($n));
    $R = $sigma->dot($new);
    $R = $R->getData();
    $mu = $mu->getData();

    
    for($i = 0; $i < $n; $i++){
        $R[0][$i] = $mu[0] + $R[0][$i];
        $R[1][$i] = $mu[0] + $R[1][$i];
    }
    
    //$R = new NumPHP\Core\NumArray($R);

    //print_r($sigma->dot($n));

    // $mu = [R(:,1) R(:,2) logQS_1*ones(size(R(:,1))) IF_1*ones(size(R(:,1)))];
    $logQS_1_ones = [];
    for($i = 0; $i < $n; $i++){
        array_push($logQS_1_ones, $logQS_1 * 1);
    }
    $IF_1_ones = [];
    for($i = 0; $i < $n; $i++){
        array_push($IF_1_ones, $IF_1 * 1);
    }
    $mu = [$R[0], $R[1], $logQS_1_ones,  $IF_1_ones];

    $j = [0, 0, 0, 0];
    $D = [0, 0, 0, 0];

    //print_r($mu);
    //print_r($mu[0]);
    //die;

    $mu = array_map(null, ...$mu);

    for($i = 0; $i < $n; $i++){

        for($a = 0; $a < 4; $a++){
            $euclidean = new Phpml\Math\Distance\Euclidean();
            $D[$a] = $euclidean->distance($X[$a], $mu[$i]);
        }
        //print_r($D);
        $M = min($D);
        $I = array_search($M, $D);

        if ($I == 0) {    
            $j[0] = $j[0] + 1; 
        }elseif ($I == 1){
            $j[1] = $j[1] + 1; 
        }elseif ($I == 2){
            $j[2] = $j[2] + 1; 
        }elseif ($I == 3){
            $j[3] = $j[3] + 1;
        }
    }

    //print_r($j);
    //echo "<br>";
    
    $max_prob = max($j);
    $near_clust = array_search($max_prob, $j);

    //$R =  $mu + $sigma->dot(boxMuller(1000));

    //boxMuller

    ///$R =  $mu + $sigma->dot(boxMuller(1000));

    // R = mvnrnd(ESP_multi,V_multi,1000);

    //print_r($Clust);
    //die;
    /*

    $_inf_year = min($Clust[$near_clust][3]);
    $_sup_year = max($Clust[$near_clust][3]);
    */

    $_inf_year = min($Clust[$near_clust][3]);
    $_sup_year = max($Clust[$near_clust][3]);

    print_r($_inf_year);
    echo "<br>";
    print_r($_sup_year);



    $_inf_qs = ceil(pow(10, min($Clust[$near_clust][2])));
    $_sup_qs = floor(pow(10, max($Clust[$near_clust][2])));  

    echo "<br>";
    print_r($_inf_qs);
    echo "<br>";
    print_r($_sup_qs);

    /*
        result = 
        ['You will be hired in ',num2str(min(Clust{near_clust}(:,4))),
        ' to ',num2str(max(Clust{near_clust}(:,4))),
        ' years since your graduation, in a university with a QS rank between 
        ',num2str(ceil(10^(min(Clust{near_clust}(:,3))))),
        ' and ',num2str(floor(10^(max(Clust{near_clust}(:,3))))),'.'];
*/
    //echo 'Salida mu <br>';
    //print_r($ESP_multi);
    //print_r($mu);
    
    //echo 'Salida sigma <br>';
    //print_r($V_multi);
    //print_r($sigma);


?>


