<?php

    include 'vendor/autoload.php';

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

    $Data_C1 = new NumPHP\Core\NumArray($_data_C1);
    $Data_C2 = new NumPHP\Core\NumArray($_data_C2);
    $Data_C3 = new NumPHP\Core\NumArray($_data_C3);
    $Data_C4 = new NumPHP\Core\NumArray($_data_C4);

    // init cluster
    $Clust = new NumPHP\Core\NumArray([$Data_C1, $Data_C2, $Data_C3, $Data_C4]);
    //print_r($Clust);


    $X = new NumPHP\Core\NumArray(
        [
            [$Data_C1->mean(0)->get(2),$Data_C1->mean(0)->get(3),$Data_C1->mean(0)->get(1),$Data_C1->mean(0)->get(4)],
            [$Data_C2->mean(0)->get(2),$Data_C2->mean(0)->get(3),$Data_C2->mean(0)->get(1),$Data_C2->mean(0)->get(4)],
            [$Data_C3->mean(0)->get(2),$Data_C3->mean(0)->get(3),$Data_C3->mean(0)->get(1),$Data_C3->mean(0)->get(4)],
            [$Data_C4->mean(0)->get(2),$Data_C4->mean(0)->get(3),$Data_C4->mean(0)->get(1),$Data_C4->mean(0)->get(4)]
        ]);
    //rint_r($X );

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
    ]);

    $CO_11      = [array($CO[0][0],$CO[0][1]), array($CO[1][0],$CO[1][1])];
    $CO_12      = [array($CO[0][2],$CO[0][3]), array($CO[1][2],$CO[1][3])];
    $CO_21      = [array($CO[2][0],$CO[2][1]), array($CO[3][0],$CO[3][1])];
    $CO_22      = [array($CO[2][2],$CO[2][3]), array($CO[3][2],$CO[3][3])];

    $XY_m       = [array_sum($V_2)/count($V_2) , array_sum($V_3)/count($V_3)];
    $AB_m       = [array_sum($V_1)/count($V_1) , array_sum($V_4)/count($V_4)];

    //ESP_multi = XY_m + (CO_12*inv(CO_22)*(AB_0 - AB_m)')';
    //$invCO_22   = [ [1.7903, 0.0624],[0.0624, 0.0400] ];
          
    $AB_0_AB_m  = [ [$AB_0[0] - $AB_m[0]], [$AB_0[1] - $AB_m[1]]];

    $CO_12_invCO_22 = [ [0.1940, -0.0335],
                        [0.6539, 0.0046]];

    $CO_12_invCO_22_AB_0_AB_m = [   [$CO_12_invCO_22[0][0]*$AB_0_AB_m[0][0]+$CO_12_invCO_22[0][1]*$AB_0_AB_m[1][0]],
                                    [$CO_12_invCO_22[1][0]*$AB_0_AB_m[0][0]+$CO_12_invCO_22[1][1]*$AB_0_AB_m[1][0]]];

    $CO_12_invCO_22_AB_0_AB_m = [$CO_12_invCO_22_AB_0_AB_m[0][0], $CO_12_invCO_22_AB_0_AB_m[1][0]];

    $ESP_multi  = [$XY_m[0]+$CO_12_invCO_22_AB_0_AB_m[0], $XY_m[1]+$CO_12_invCO_22_AB_0_AB_m[1]];

    //V_multi = CO_11 - CO_12*inv(CO_22)*CO_21;
    $V_multi    = array(
                        array(0.2646, 0.2874),
                        array(0.2874, 7.6525));

    die;

?>


