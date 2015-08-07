<?php

 require __DIR__ . '/../bootstrap.php';

 use Tester\Assert;

$latte = new Latte\Engine;
 $latte->setLoader(new Latte\Loaders\StringLoader);

 Class Translator implements Latte\Runtime\ITranslator 
 {

     function translate($message, $count = NULL) 
     {
         return "translated test string";
     }

 }

 $latte->setTranslator(new Translator);

 $output = $latte->renderToString("{_}test string{/_}");

 Assert::same("translated test string", $output);
 