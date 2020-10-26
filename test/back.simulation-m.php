<?php

    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


    function rand_float($st_num=0, $end_num=1, $mul=1000000){
        if ($st_num>$end_num) return false;
            return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }
	
	if (isset($_POST['calculate'])) {

        //logQS(PhD)
        $V_1        = [];
        //logQS(HU)
        $V_2        = [];
        //Hiring Year
        $V_3        = [];
        //Impact Factor @ Hiring Year
        $V_4        = [];
        $archivo    = fopen("Var-logqs-asist.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($V_1, floatval($new_data[1]));
            array_push($V_2, floatval($new_data[2]));
            array_push($V_3, floatval($new_data[3]));
            array_push($V_4, floatval($new_data[4]));
        }fclose($archivo);        
        //Lectura de clusters
        //Data_C1
        $Data_C1    = [];
        $archivo    = fopen("Cluster_log1.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($Data_C1, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
        }fclose($archivo);
        $Data_C1    = array_map(null, ...$Data_C1);
        //Data_C2
        $Data_C2    = [];
        $archivo    = fopen("Cluster_log2.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($Data_C2, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
        }fclose($archivo);
        $Data_C2    = array_map(null, ...$Data_C2);
        //Data_C3
        $Data_C3    = [];
        $archivo    = fopen("Cluster_log3.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($Data_C3, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
        }fclose($archivo);
        $Data_C3 = array_map(null, ...$Data_C3);
        //Data_C4
        $Data_C4    = [];
        $archivo    = fopen("Cluster_log4.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($Data_C4, [floatval($new_data[0]),floatval($new_data[1]),floatval($new_data[2]),floatval($new_data[3]),floatval($new_data[4])]);
        }fclose($archivo);
        $Data_C4    = array_map(null, ...$Data_C4);
        //Clust{1} Clust{2} Clust{3} Clust{4}
        $clust      = [];
        array_push($clust, [$Data_C1, $Data_C2, $Data_C3, $Data_C4]);
        //X{1} X{2} X{3} X{4}
        $x = [];
        array_push($x, [array_sum($Data_C1[2])/count($Data_C1[2]),array_sum($Data_C1[3])/count($Data_C1[3]),array_sum($Data_C1[1])/count($Data_C1[1]),array_sum($Data_C1[4])/count($Data_C1[4])]);
        array_push($x, [array_sum($Data_C2[2])/count($Data_C2[2]),array_sum($Data_C2[3])/count($Data_C2[3]),array_sum($Data_C2[1])/count($Data_C2[1]),array_sum($Data_C2[4])/count($Data_C2[4])]);
        array_push($x, [array_sum($Data_C3[2])/count($Data_C3[2]),array_sum($Data_C3[3])/count($Data_C3[3]),array_sum($Data_C3[1])/count($Data_C3[1]),array_sum($Data_C3[4])/count($Data_C3[4])]);
        array_push($x, [array_sum($Data_C4[2])/count($Data_C4[2]),array_sum($Data_C4[3])/count($Data_C4[3]),array_sum($Data_C4[1])/count($Data_C4[1]),array_sum($Data_C4[4])/count($Data_C4[4])]);
        $XY         = [$V_2, $V_3];
        $AB         = [$V_1, $V_4];

        /*
		$QS_1       = floatval($_POST['preg_1']);
		$logQS_1    = log10($QS_1);
        $IF_1       = floatval($_POST['preg_2']);
        */
        $QS_1       = floatval(0.5);
		$logQS_1    = log10($QS_1);
        $IF_1       = floatval(0.3);
        $AB_0       = [$logQS_1,$IF_1];
        /* Problema en el calculo de la covarianza de una matriz ==> Solucion: Datos de calculo cargados a mano desde MATLAB */
        $CO         = array(
                            array( 0.3285,   0.3776,  0.1455,  -1.0649),
                            array( 0.3776,   7.9001,  0.3820,  -0.4802),
                            array( 0.1455,   0.3820,  0.5907,  -0.9224),
                            array(-1.0649,  -0.4802, -0.9224,  26.4672));

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

        //R = mvnrnd(ESP_multi,V_multi,1000);
        $_iterator = 10;
        $R = [];
        for($i = 0; $i < $_iterator; $i++){
            array_push($R, [rand_float(),rand_float()]);
        }
        print_r($R);

        die;

        $R          = [];                
        $archivo    = fopen("R.csv", "r");
        while (($datos = fgetcsv($archivo, ";")) == true) {
            $new_data = explode(";",$datos[0]);
            array_push($R, [floatval($new_data[0]),floatval($new_data[1])]);
        }fclose($archivo);

        //j = zeros(1,4);
        $j          = array(0, 0, 0, 0);
        //D = j;
        $D          = array(0, 0, 0, 0);



        
        //print_r($R);


        
        
    
        


        $result     = 1;
		 
		echo $QS_1;
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Simulation.m">
    <meta name="author" content="jt-ingemat-uv">

    <title>Simulation.m</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<html>
    <body>
        <form>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </body>
</html>



