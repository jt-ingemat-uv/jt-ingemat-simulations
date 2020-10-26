<?php 

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

function genererNombreLoiNormale($mu, $sigma) {

    // On récupère deux nombres pseudo-aléatoires indépendants selon une loi uniforme sur l'intervalle [0;1]
    $randNumUni = rand(0,999) / 1000;
    $randNumBi = rand(0,999) / 1000;

    // On récupère un nombre pseudo-aléatoire selon une loi normale centrée réduite
    // (Paramètres : moyenne = 0, écart-type = 1)
    // Utilisation de l'algorithme de Box-Muller
    $randNumNorm = sqrt(-2.0*log($randNumUni))*cos(( 2.0 * 3.141592653589793238462643383279502884197169399375 )*$randNumBi);

    return ($mu + $sigma * $randNumNorm);
}

/*$ESP_multi = [ 9.4758, 8.0365 ];
/$V_multi = [ 
            [ 0.2646, 0.2874],
            [ 0.2874, 7.6525]];


/*print_r(boxMuller(10));



?>