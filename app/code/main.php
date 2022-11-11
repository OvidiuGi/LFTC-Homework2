<?php

require './LexicalScanner.php';

$file = fopen('file.txt','r');
$symbolTableFile = fopen('symbolTable.txt', 'w');
$programInternalFormFile = fopen('programInternalForm.txt', 'w');
$errorTableFile = fopen('errorTable.txt', 'w');

$lexicalScanner = new LexicalScanner();

$lexicalScanner->setInput($file);

$lexicalScanner->getSymbolTableFile($symbolTableFile);

$lexicalScanner->getProgramInternalFormFile($programInternalFormFile);

$lexicalScanner->getErrorFile($errorTableFile, $file);
