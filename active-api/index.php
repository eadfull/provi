<?php

require '../Source/Models/LeadLovers.php';

use Source\Models\LeadLovers AS Active;

$active = new Active;

//busca e-mail
// $active->getByEmail("rubimneuza@hotmail.com");
// var_dump($active->getCallback());

$active->addTagLead("contato@fokusf5.com.br", 139215);
var_dump($active->getCallback());

//lista maquinas
// echo "<pre>";
// $active->getTags();
// var_dump($active->getCallback());

// foreach ($active->getCallback() as $MACHINE) {
// 	print_r($MACHINE);
// }

//lista maquinas
// $active->getByMachine(299184);
// var_dump($active->getCallback());

//sequencia de email
// echo "<pre>";
// print_r($active);
// $active->getEmailSequency(306970);
// var_dump($active->getCallback());
 
//machine = 306970
//sequecy = 694611
//level = 2865621
//

//machine = 22796
//sequecy = 26649
//level = 2865621

// //Level de email
// $active->getSequenceLevelCode(299184, 693823);
// var_dump($active->getCallback());

//add new lead
 // $active->addLead("Marcos Aurelio", "marquim_sb@hotmail.com", 306970, 694611, 1,"Developer By");
 // var_dump($active->getCallback()); 
 // 
 // $active->addLead("Marcos Aurelio", "marquim_sb@hotmail.com", 22796, 26649, 1);
 // var_dump($active->getCallback()); 
