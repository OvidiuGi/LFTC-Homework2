<?php

require './LexicalScanner.php';

$file = fopen('file.txt','r');
$symbolTableFile = fopen('symbolTable.txt', 'w');
$programInternalFormFile = fopen('programInternalForm.txt', 'w');
$errorTableFile = fopen('errorTable.txt', 'w');

$lexicalScanner = new LexicalScanner();

//Inside the LexicalScanner class, set the input from file.txt
$lexicalScanner->setInput($file);
//with the input from file.txt, the function forms the SymbolTable array,
//the PIF array and Error array
//Writes in a file the Symbol table
$lexicalScanner->getSymbolTableFile($symbolTableFile);
//Writes in a file the PIF table
$lexicalScanner->getProgramInternalFormFile($programInternalFormFile);
//Writes in a file the Error table
$lexicalScanner->getErrorFile($errorTableFile, $file);
