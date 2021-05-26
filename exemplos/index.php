<?php

require '../Source/Models/Provi.php';


use Source\Models\Provi;

$provi = new Provi;

 $provi->getByCheckout(221034);
// $provi->getCourses();



echo "<pre>";
var_dump($provi->getCallback());