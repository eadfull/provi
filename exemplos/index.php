<?php

require '../Source/Models/Provi.php';


use Source\Models\Provi;

$provi = new Provi;

 $provi->getByCheckout(221034);
// $provi->getCourses();

var_dump($provi->getCallback());
//busca e-mail
// $active->getByEmail("fokusf5s@gmail.com");
// if($active->getCallback() && !empty($active->getCallback()->Code)):
//    var_dump($active->getCallback()->Code);
// endif;

// $active->addTagLead("contato@fokusf5.com.br", 139215);
// var_dump($active->getCallback());

//lista maquinas
echo "<pre>";
// $active->getTags();
//  //var_dump($active->getCallback()->Tags);

// foreach ($active->getCallback()->Tags as $MACHINE) {
// 	print_r($MACHINE);
// }
return;
//lista maquinas
$active->getByMachine(368428);
var_dump($active->getCallback());

//sequencia de email
// echo "<pre>";
// print_r($active);
 $active->getEmailSequency(368428);
 var_dump($active->getCallback());
 return;
//machine = 299184
//sequecy = 697893
//level = 2884279
//

//machine = 22796
//sequecy = 26649
//level = 2865621

// //Level de email
//$active->getSequenceLevelCode(299184, 697893);
// var_dump($active->getCallback());

//add new lead
 // $active->addLead("Marcos Aurelio", "marquim_sb@hotmail.com", 306970, 694611, 1,"Developer By");
 // var_dump($active->getCallback()); 
 // 
 // $active->addLead("Marcos Aurelio", "marquim_sb@hotmail.com", 22796, 26649, 1);
 // var_dump($active->getCallback()); 
