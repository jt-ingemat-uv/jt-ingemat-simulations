<?php 

//echo "funciona el php";
//die;
require_once __DIR__ . '/vendor/autoload.php';
//include './vendor/autoload.php';

use  NumPHP\LinAlg\LinAlg;


$vista_resultado = FALSE;
$no_autorizado = FALSE;

function boxMuller($n){
    $_M  = [];
    $_C2 = [];
    $i   = 0;
    while($n >  $i){
        $U1 = rand(0,999) / 1000;
        $U2 = rand(0,999) / 1000;
        $Z1 = sqrt(-2.0 * log($U1)) * cos(( 2.0 * pi() ) * $U2);
        $Z2 = sqrt(-2.0 * log($U1)) * sin(( 2.0 * pi() ) * $U2);
        array_push($_M, [$Z1, $Z2]);
        $i++;
    }
    return $_M;
}

if (isset($_POST['calculate'])) {
    $token = isset($_POST["token"]) ? $_POST["token"] : "";
    if($token == "fsdtyu234jkhfsd8234"){
        $preg_1 = isset($_POST["preg_1"]) ? floatval($_POST["preg_1"]) : 0;
        $preg_2 = isset($_POST["preg_2"]) ? floatval($_POST["preg_2"]) : 0;
        if($preg_1 > 0 && $preg_2 > 0){

            $vista_resultado = TRUE;
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

            $X = [];
            array_push($X,
                    [   array_sum($Data_C1[2])/count($Data_C1[2]),
                        array_sum($Data_C1[3])/count($Data_C1[3]),
                        array_sum($Data_C1[1])/count($Data_C1[1]),
                        array_sum($Data_C1[4])/count($Data_C1[4])
                    ] );
            array_push($X,
                    [   array_sum($Data_C2[2])/count($Data_C2[2]),
                        array_sum($Data_C2[3])/count($Data_C2[3]),
                        array_sum($Data_C2[1])/count($Data_C2[1]),
                        array_sum($Data_C2[4])/count($Data_C2[4])
                    ] );
            array_push($X,
                    [   array_sum($Data_C3[2])/count($Data_C3[2]),
                        array_sum($Data_C3[3])/count($Data_C3[3]),
                        array_sum($Data_C3[1])/count($Data_C3[1]),
                        array_sum($Data_C3[4])/count($Data_C3[4])
                    ] );
            array_push($X,
                    [   array_sum($Data_C4[2])/count($Data_C4[2]),
                        array_sum($Data_C4[3])/count($Data_C4[3]),
                        array_sum($Data_C4[1])/count($Data_C4[1]),
                        array_sum($Data_C4[4])/count($Data_C4[4])
                    ] );
        
            $XY = [$V_2->getData(), $V_3->getData()];
            $AB = [$V_1->getData(), $V_4->getData()];

            $QS_1    = $preg_1;
            $logQS_1 = log10($QS_1);
            $IF_1    = $preg_2;
        
            $AB_0 = [$logQS_1, $IF_1];
            
            //$M_CO = [$XY, $AB];
        
            $CO = array(
                [ 0.3285,   0.3776,  0.1455,  -1.0649],
                [ 0.3776,   7.9001,  0.3820,  -0.4802],
                [ 0.1455,   0.3820,  0.5907,  -0.9224],
                [-1.0649,  -0.4802, -0.9224,  26.4672]
                );
        
            $CO_11 = [
                [ $CO[0][0], $CO[0][1] ], 
                [ $CO[1][0], $CO[1][1] ] ];
            $CO_12 = [
                [ $CO[0][2], $CO[0][3] ], 
                [ $CO[1][2], $CO[1][3] ] ];
            $CO_21 = [
                [ $CO[2][0], $CO[2][1] ], 
                [ $CO[3][0], $CO[3][1] ] ];
            $CO_22 = [
                [ $CO[2][2], $CO[2][3] ], 
                [ $CO[3][2], $CO[3][3] ] ];
        
            $XY_m = [
                array_sum($V_2->getData())/count($V_2->getData()), 
                array_sum($V_3->getData())/count($V_3->getData()) ];
            $AB_m = [
                array_sum($V_1->getData())/count($V_1->getData()),
                array_sum($V_4->getData())/count($V_4->getData()) ];
            
            $CO_11N = new NumPHP\Core\NumArray($CO_11);
            $CO_12N = new NumPHP\Core\NumArray($CO_12);
            $CO_21N = new NumPHP\Core\NumArray($CO_21);
            $CO_22N = new NumPHP\Core\NumArray($CO_22);

            // ESP_multi = XY_m + (CO_12*inv(CO_22)*(AB_0 - AB_m)')';

            $AB_0N = new NumPHP\Core\NumArray($AB_0);
            $AB_mN = new NumPHP\Core\NumArray($AB_m);

            $AB_0_AB_m = $AB_0N->add($AB_mN->dot(-1))->getData();
            /* Transpose */
            $_aux = [];
            foreach ($AB_0_AB_m as $value) {
                array_push($_aux, [$value]);
            }
            
            $AB_0_AB_m = new NumPHP\Core\NumArray($_aux);
            $XY_m = new NumPHP\Core\NumArray([$XY_m]);

            $ESP_multi = $XY_m->add($CO_12N->dot(LinAlg::inv($CO_22N)->dot($AB_0_AB_m))->getTranspose())->getData();
            $ESP_multi = new NumPHP\Core\NumArray($ESP_multi); 

            unset($CO_12N);

            $CO_12N = new NumPHP\Core\NumArray($CO_12);
            
            $V_multi = $CO_11N->add($CO_12N->dot(LinAlg::inv($CO_22N)->dot($CO_21N))->dot(-1));

            $sigma = $V_multi->getData();
            unset($V_multi);
            foreach ($sigma as $fil => $fila) {
                foreach ($fila as $key => $value) {
                    $sigma[$fil][$key] = round($sigma[$fil][$key], 8);
                }
            }
            $V_multi = new NumPHP\Core\NumArray($sigma);
            
            /* 
            R = mvnrnd(ESP_multi,   V_multi,                1000); 
            R = mvnrnd(mu,          cholesky($V_multi),     boxMuller($_iter))
            R = mu + At*A
            */
            $_iter = 1000;
            $n = new NumPHP\Core\NumArray(boxMuller($_iter));

            $_aux = $ESP_multi->getData();
            $_aux = array_map(null, ...$_aux);

            $R = $n->dot(LinAlg::cholesky($V_multi))->getData();
            foreach ($R as $key => $value) {
                $R[$key] = [ $R[$key][0] + $_aux[0] , $R[$key][1] + $_aux[1]];
            }
            
            /* Estructura de asignacion MATLAB
            mu = [
                R(:,1) 
                R(:,2) 
                logQS_1*ones(size(R(:,1))) 
                IF_1*ones(size(R(:,1)))];
            */

            $mu_R1 = $mu_R2 = $logQS_1_ones = $IF_1_ones = [];

            for($i = 0; $i < $_iter; $i++){
                array_push($mu_R1,  $R[$i][0]);
                array_push($mu_R2,  $R[$i][1]);
                array_push($logQS_1_ones, $logQS_1 * 1);
                array_push($IF_1_ones, $IF_1 * 1);
            }
            
            $mu = [ 
                $mu_R1,
                $mu_R2,
                $logQS_1_ones,
                $IF_1_ones
            ];


            $mu = array_map(null, ...$mu);
        
            $j = [0, 0, 0, 0];
            $D = [0, 0, 0, 0];

            for($i = 0; $i < $_iter; $i++){
        
                for($a = 0; $a < 4; $a++){
                    $euclidean = new Phpml\Math\Distance\Euclidean();
                    $D[$a] = $euclidean->distance($X[$a], $mu[$i]);

                }

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
            
            $max_prob = max($j);
            $near_clust = array_search($max_prob, $j);
            $m_pr = $max_prob/1000;
            $j_pr = [];
            foreach ($j as $value) {
                array_push($j_pr, $value/1000);
            }
            $color = [
                "blue", 
                "red", 
                "yellow", 
                "purple"
            ];
            
            $centroid = [
                "The assistant-centroid for the blue cluster is represented by the vector QS Rank of graduation University as PhD degree = 28,QS Rank of Hiring University as Assistant Professor = 40, Numbers of years since PhD degree to Assistant Professorship Appointment = 4, CMoJIF at hiring year as Assistant Professor = 11.92",
                "The assistant-centroid for the red cluster is represented by the vector, QS Rank of graduation University as PhD degree = 76, QS Rank of Hiring University as Assistant Professor = 123, Numbers of years since PhD degree to Assistant Professorship Appointment = 10, CMoJIF at hiring year as Assistant Professor = 7.09",
                "The assistant-centroid for the yellow cluster is represented by the vector QS Rank of graduation University as PhD degree = 60, QS Rank of Hiring University as Assistant Professor = 72, Numbers of years since PhD degree to Assistant Professorship Appointment = 3, CMoJIF at hiring year as Assistant Professor = 5.66",
                "The assistant-centroid for the purple cluster is represented by the vector QS Rank of graduation University as PhD degree = 30, QS Rank of Hiring University as Assistant Professor = 26, Numbers of years since PhD degree to Assistant Professorship Appointment = 5, CMoJIF at hiring year as Assistant Professor = 19.79"
            ];
                
            $select_color = $color[$near_clust];
            $select_centroid = $centroid[$near_clust];
            
            $Clust_evaluate = array_map(null, ...$Clust[$near_clust]);

            $year_min = [min($Clust_evaluate[0]),min($Clust_evaluate[1]),min($Clust_evaluate[2]),min($Clust_evaluate[3]),min($Clust_evaluate[4])];
            $year_max = [max($Clust_evaluate[0]),max($Clust_evaluate[1]),max($Clust_evaluate[2]),max($Clust_evaluate[3]),max($Clust_evaluate[4])];

            $_inf_year = $year_min[3];
            $_sup_year = $year_max[3];

            $_inf_qs = ceil(pow(10, $year_min[2]));
            $_sup_qs = floor(pow(10, $year_max[2])); 
            
            $result = "You have a ".number_format($m_pr, 2)." probability to belong to ".$select_color." cluster with a range of ".number_format($_inf_qs, 2)." and ".number_format($_sup_qs, 2)." and ".number_format($_inf_year, 2)." and ".number_format($_sup_year,2)." years for the QS rank of Hiring university as assistant professor, and hiring year as assistant professor since the graduation year, respectively.";
        }
    }else{
        $vista_resultado = TRUE;
        $no_autorizado = TRUE;

    }

   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>simulation.m</title>

    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

    <!-- Main CSS-->
    <style> 
        /* ==========================================================================
        #FONT
        ========================================================================== */
        .font-robo {
        font-family: "Roboto", "Arial", "Helvetica Neue", sans-serif;
        }

        .font-poppins {
        font-family: "Poppins", "Arial", "Helvetica Neue", sans-serif;
        }

        .font-opensans {
        font-family: "Open Sans", "Arial", "Helvetica Neue", sans-serif;
        }

        /* ==========================================================================
        #GRID
        ========================================================================== */
        .row {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        }

        .row .col-2:last-child .input-group-desc {
        margin-bottom: 0;
        }

        .row-space {
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        }

        .row-refine {
        margin: 0 -15px;
        }

        .row-refine .col-3 .input-group-desc,
        .row-refine .col-9 .input-group-desc {
        margin-bottom: 0;
        }

        .col-2 {
        width: -webkit-calc((100% - 30px) / 2);
        width: -moz-calc((100% - 30px) / 2);
        width: calc((100% - 30px) / 2);
        }

        @media (max-width: 767px) {
        .col-2 {
            width: 100%;
        }
        }

        .form-row {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-box-align: start;
        -webkit-align-items: flex-start;
        -moz-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        padding: 24px 55px;
        border-bottom: 1px solid #e5e5e5;
        }

        .form-row .name {
        width: 188px;
        color: #333;
        font-size: 15px;
        font-weight: 700;
        margin-top: 11px;
        }

        .form-row .value {
        width: -webkit-calc(100% - 188px);
        width: -moz-calc(100% - 188px);
        width: calc(100% - 188px);
        }

        @media (max-width: 767px) {
        .form-row {
            display: block;
            padding: 24px 30px;
        }
        .form-row .name,
        .form-row .value {
            display: block;
            width: 100%;
        }
        .form-row .name {
            margin-top: 0;
            margin-bottom: 12px;
        }
        }

        /* ==========================================================================
        #BOX-SIZING
        ========================================================================== */
        /**
        * More sensible default box-sizing:
        * css-tricks.com/inheriting-box-sizing-probably-slightly-better-best-practice
        */
        html {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        }

        * {
        padding: 0;
        margin: 0;
        }

        *, *:before, *:after {
        -webkit-box-sizing: inherit;
        -moz-box-sizing: inherit;
        box-sizing: inherit;
        }

        /* ==========================================================================
        #RESET
        ========================================================================== */
        /**
        * A very simple reset that sits on top of Normalize.css.
        */
        body,
        h1, h2, h3, h4, h5, h6,
        blockquote, p, pre,
        dl, dd, ol, ul,
        figure,
        hr,
        fieldset, legend {
        margin: 0;
        padding: 0;
        }

        /**
        * Remove trailing margins from nested lists.
        */
        li > ol,
        li > ul {
        margin-bottom: 0;
        }

        /**
        * Remove default table spacing.
        */
        table {
        border-collapse: collapse;
        border-spacing: 0;
        }

        /**
        * 1. Reset Chrome and Firefox behaviour which sets a `min-width: min-content;`
        *    on fieldsets.
        */
        fieldset {
        min-width: 0;
        /* [1] */
        border: 0;
        }

        button {
        outline: none;
        background: none;
        border: none;
        }

        /* ==========================================================================
        #PAGE WRAPPER
        ========================================================================== */
        .page-wrapper {
        min-height: 100vh;
        }

        body {
        background-color: #000000;
        font-family: "Open Sans", "Arial", "Helvetica Neue", sans-serif;
        font-weight: 400;
        font-size: 14px;
        }

        h1, h2, h3, h4, h5, h6 {
        font-weight: 400;
        }

        h1 {
        font-size: 36px;
        }

        h2 {
        font-size: 30px;
        }

        h3 {
        font-size: 24px;
        }

        h4 {
        font-size: 18px;
        }

        h5 {
        font-size: 15px;
        }

        h6 {
        font-size: 13px;
        }

        /* ==========================================================================
        #BACKGROUND
        ========================================================================== */
        .bg-blue {
        background: #2c6ed5;
        }

        .bg-red {
        background: #fa4251;
        }

        .bg-dark {
        background: #1a1a1a;
        }

        .bg-gra-01 {
        background: -webkit-gradient(linear, left bottom, left top, from(#fbc2eb), to(#a18cd1));
        background: -webkit-linear-gradient(bottom, #fbc2eb 0%, #a18cd1 100%);
        background: -moz-linear-gradient(bottom, #fbc2eb 0%, #a18cd1 100%);
        background: -o-linear-gradient(bottom, #fbc2eb 0%, #a18cd1 100%);
        background: linear-gradient(to top, #fbc2eb 0%, #a18cd1 100%);
        }

        .bg-gra-02 {
        background: -webkit-gradient(linear, left bottom, right top, from(#fc2c77), to(#6c4079));
        background: -webkit-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: -moz-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: -o-linear-gradient(bottom left, #fc2c77 0%, #6c4079 100%);
        background: linear-gradient(to top right, #fc2c77 0%, #6c4079 100%);
        }

        .bg-gra-03 {
        background: -webkit-gradient(linear, left bottom, right top, from(#08aeea), to(#b721ff));
        background: -webkit-linear-gradient(bottom left, #08aeea 0%, #b721ff 100%);
        background: -moz-linear-gradient(bottom left, #08aeea 0%, #b721ff 100%);
        background: -o-linear-gradient(bottom left, #08aeea 0%, #b721ff 100%);
        background: linear-gradient(to top right, #08aeea 0%, #b721ff 100%);
        }

        /* ==========================================================================
        #SPACING
        ========================================================================== */
        .p-t-100 {
        padding-top: 100px;
        }

        .p-t-130 {
        padding-top: 130px;
        }

        .p-t-180 {
        padding-top: 180px;
        }

        .p-t-45 {
        padding-top: 45px;
        }

        .p-t-20 {
        padding-top: 20px;
        }

        .p-t-15 {
        padding-top: 15px;
        }

        .p-t-10 {
        padding-top: 10px;
        }

        .p-t-30 {
        padding-top: 30px;
        }

        .p-b-100 {
        padding-bottom: 100px;
        }

        .p-b-50 {
        padding-bottom: 50px;
        }

        .m-r-45 {
        margin-right: 45px;
        }

        .m-r-55 {
        margin-right: 55px;
        }

        .m-b-55 {
        margin-bottom: 55px;
        }

        /* ==========================================================================
        #WRAPPER
        ========================================================================== */
        .wrapper {
        margin: 0 auto;
        }

        .wrapper--w960 {
        max-width: 960px;
        }

        .wrapper--w900 {
        max-width: 900px;
        }

        .wrapper--w790 {
        max-width: 790px;
        }

        .wrapper--w780 {
        max-width: 780px;
        }

        .wrapper--w680 {
        max-width: 680px;
        }

        /* ==========================================================================
        #BUTTON
        ========================================================================== */
        .btn {
        display: inline-block;
        line-height: 50px;
        padding: 0 30px;
        -webkit-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        transition: all 0.4s ease;
        cursor: pointer;
        font-size: 15px;
        text-transform: capitalize;
        font-weight: 700;
        color: #fff;
        font-family: inherit;
        }

        .btn--radius {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        }

        .btn--radius-2 {
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        }

        .btn--pill {
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        }

        .btn--green {
        background: #57b846;
        }

        .btn--green:hover {
        background: #4dae3c;
        }

        .btn--blue {
        background: #4272d7;
        }

        .btn--blue:hover {
        background: #3868cd;
        }

        .btn--blue-2 {
        background: #2c6ed5;
        }

        .btn--blue-2:hover {
        background: #185ac1;
        }

        .btn--red {
        background: #ff4b5a;
        }

        .btn--red:hover {
        background: #eb3746;
        }

        /* ==========================================================================
        #DATE PICKER
        ========================================================================== */
        td.active {
        background-color: #2c6ed5;
        }

        input[type="date" i] {
        padding: 14px;
        }

        .table-condensed td, .table-condensed th {
        font-size: 14px;
        font-family: "Roboto", "Arial", "Helvetica Neue", sans-serif;
        font-weight: 400;
        }

        .daterangepicker td {
        width: 40px;
        height: 30px;
        }

        .daterangepicker {
        border: none;
        -webkit-box-shadow: 0px 8px 20px 0px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 8px 20px 0px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 8px 20px 0px rgba(0, 0, 0, 0.15);
        display: none;
        border: 1px solid #e0e0e0;
        margin-top: 5px;
        }

        .daterangepicker::after, .daterangepicker::before {
        display: none;
        }

        .daterangepicker thead tr th {
        padding: 10px 0;
        }

        .daterangepicker .table-condensed th select {
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        font-size: 14px;
        padding: 5px;
        outline: none;
        }

        /* ==========================================================================
        #FORM
        ========================================================================== */
        input,
        textarea {
        outline: none;
        margin: 0;
        border: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        width: 100%;
        font-size: 14px;
        font-family: inherit;
        }

        textarea {
        resize: none;
        }

        .input-group {
        position: relative;
        margin: 0;
        }

        .input--style-6,
        .textarea--style-6 {
        background: transparent;
        line-height: 38px;
        border: 1px solid #cccccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        color: #666;
        font-size: 15px;
        -webkit-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        transition: all 0.4s ease;
        padding: 0 20px;
        }

        .input--style-6::-webkit-input-placeholder,
        .textarea--style-6::-webkit-input-placeholder {
        /* WebKit, Blink, Edge */
        color: #999;
        }

        .input--style-6:-moz-placeholder,
        .textarea--style-6:-moz-placeholder {
        /* Mozilla Firefox 4 to 18 */
        color: #999;
        opacity: 1;
        }

        .input--style-6::-moz-placeholder,
        .textarea--style-6::-moz-placeholder {
        /* Mozilla Firefox 19+ */
        color: #999;
        opacity: 1;
        }

        .input--style-6:-ms-input-placeholder,
        .textarea--style-6:-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #999;
        }

        .input--style-6:-ms-input-placeholder,
        .textarea--style-6:-ms-input-placeholder {
        /* Microsoft Edge */
        color: #999;
        }

        .input--style-6:focus,
        .textarea--style-6:focus {
        -webkit-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.15);
        -webkit-transform: translateY(-3px);
        -moz-transform: translateY(-3px);
        -ms-transform: translateY(-3px);
        -o-transform: translateY(-3px);
        transform: translateY(-3px);
        }

        .textarea--style-6 {
        line-height: 1.2;
        min-height: 120px;
        padding: 10px 20px;
        }

        .label--desc {
        font-size: 13px;
        color: #999;
        margin-top: 10px;
        }

        @media (max-width: 767px) {
        .label--desc {
            margin-top: 14px;
        }
        }

        .input-file {
        display: none;
        }

        .input-file + label {
        font-size: 15px;
        color: #fff;
        color: white;
        line-height: 40px;
        background-color: #666666;
        padding: 0 20px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        display: inline-block;
        margin-right: 15px;
        -webkit-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        transition: all 0.4s ease;
        cursor: pointer;
        }

        .input-file:focus + label,
        .input-file + label:hover {
        background: #1b1b1b;
        }

        .input-file__info {
        font-size: 15px;
        color: #666;
        }

        @media (max-width: 767px) {
        .input-file__info {
            display: block;
            margin-top: 6px;
        }
        }


        /* ==========================================================================
        #TITLE
        ========================================================================== */
        .title {
        font-size: 36px;
        font-weight: 700;
        text-align: left;
        color: #fff;
        margin-bottom: 24px;
        }
        
        .subtitle {
        font-size: 8px;
        font-weight: 100;
        text-align: left;
        color: #fff;
        margin-bottom: 24px;
        }

        @media (max-width: 767px) {
        .title {
            padding: 0 15px;
        }
        }

        /* ==========================================================================
        #CARD
        ========================================================================== */
        .card {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        background: #fff;
        }

        .card-6 {
        background: transparent;
        }

        .card-6 .card-heading {
        background: transparent;
        }

        .card-6 .card-body {
        background: #fff;
        position: relative;
        border: 1px solid #e5e5e5;
        border-bottom: none;
        padding: 30px 0;
        padding-bottom: 0;
        -webkit-border-top-left-radius: 3px;
        -moz-border-radius-topleft: 3px;
        border-top-left-radius: 3px;
        -webkit-border-top-right-radius: 3px;
        -moz-border-radius-topright: 3px;
        border-top-right-radius: 3px;
        }

        .card-6 .card-body:before {
        bottom: 100%;
        left: 75px;
        border: solid transparent;
        content: "";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-color: rgba(255, 255, 255, 0);
        border-bottom-color: #fff;
        border-width: 10px;
        }

        .card-6 .card-footer {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-top: none;
        -webkit-border-bottom-left-radius: 3px;
        -moz-border-radius-bottomleft: 3px;
        border-bottom-left-radius: 3px;
        -webkit-border-bottom-right-radius: 3px;
        -moz-border-radius-bottomright: 3px;
        border-bottom-right-radius: 3px;
        padding: 50px 55px;
        }

        @media (max-width: 767px) {
        .card-6 .card-footer {
            padding: 50px 30px;
        }
        }

    </style> 
</head>
<body>
    <br>
        <?php if($vista_resultado== FALSE): ?>
        <div class="wrapper wrapper--w900">
            <div class="card card-6">
                <div class="card-heading">
                    <h2 class="title">simulation.m</h2>
                    <h6 class="subtitle"><?php echo "Run in php, version ".phpversion() ; ?></h6> 
                </div>
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" action="simulation.php">
                        <div class="form-row">
                            <h4>Enter authorization token</h4>
                            <br><br>
                            <input class="input--style-6" type="text" name="token" id="token">
                        </div>
                        <div class="form-row">
                            <h4>What is your Graduation University QS rank?</h4>
                            <br><br>
                            <input class="input--style-6" type="number" step="0.01" name="preg_1" id="preg_1">
                            <h6>American format numbers, example (3.4)</h6>
                        </div>
                        <div class="form-row">
                            <h4>What is your current CMoJIF average?</h4>
                            <br><br>
                            <input class="input--style-6" type="number" step="0.01" name="preg_2" id="preg_2">
                            <h6>American format numbers, example (3.4)</h6>
                            
                        </div>
                        <div class="card-footer">
                            <button class="btn btn--radius-2 btn--blue-2" type="submit" name ="calculate" value="calculate">Calculate</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="wrapper wrapper--w900">
            <div class="card card-6">
                <div class="card-heading">
                    <h2 class="title">simulation.m</h2>
                </div>
                <?php if($no_autorizado== FALSE): ?>
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" action="simulation.php">
                        <div class="form-row">
                            <br>
                            <h4> <?php echo $result; ?> </h4>
                            <br>
                            <h4> <?php echo $select_centroid; ?> </h4>
                            <br>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" action="simulation.php">
                        <div class="form-row">
                            <h4>Not Authorized</h4>
                        </div>
                    </form>
                </div>
                <?php endif ?>


            </div>
        </div>
        <?php endif ?>
    </div>
</body>

</html>
