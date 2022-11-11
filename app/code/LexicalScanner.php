<?php

class LexicalScanner
{
    public const TOKEN_TABLE = [
        0 => "identifier",
        1 => "constant",
        2 => "forall",
        3 => "between",
        4 => "(",
        5 => ")",
        6 => "{",
        7 => "}",
        8 => ";",
        9 => "=",
        10 => "<=",
        11 => "echo",
        12 => "if",
        13 => "==",
        14 => "return",
        15 => "true",
        16 => "false",
        17 => "%",
        18 => ",",
        19 => "loob"
    ];

    public array $symbolTable = [];

    public array $errorTable = [];

    public array $input = [];

    /**
     * Creates the input array, without \n and any whitespaces
     * And creates the symbolTable array, errorTable array
     *
     * @param $file
     * @return $this
     */
    public function setInput($file): self
    {
        $lineNumber = 0;
        while (!feof($file)) {
            $line = trim(fgets($file));
            $words = explode(" ", $line);

            foreach($words as $value2) {
                $this->input[] = $value2;
                if ($this->isIdentifier($value2)) {
                    $this->symbolTable[] = $value2;

                    continue;
                }

                if ($this->isConstant($value2)) {
                    $this->symbolTable[] = $value2;

                    continue;
                }

                if (!in_array($value2, self::TOKEN_TABLE)) {
                    $this->errorTable[] = $value2 . ' from line: ' .$lineNumber . ' |' . ' Lexical error';
                }
            }
            $lineNumber++;
        }

        $this->symbolTable = array_unique($this->symbolTable);
        $this->errorTable = array_unique($this->errorTable);
        $this->input = array_unique($this->input);

        return $this;
    }

    /**
     * Returns the value from TT for a specific value
     *
     * @param string $value
     * @return string|int
     */
    public function getTokenTableValue(string $value): string|int
    {
        $key = array_search($value, self::TOKEN_TABLE);

        return $key ? $key : '-';
    }

    /**
     * Returns the ST for a specific value
     *
     * @param string $value
     * @return string|int
     */
    public function getSymbolTableValue(string $value): string|int
    {
        if($this->symbolTable[0] == $value) {
            return 0;
        }

        $key = array_search($value, $this->symbolTable);

        return $key ? $key : '-';
    }

    /**
     * Cheks if the value is an identifier
     *
     * @param string $value
     * @return bool
     */
    public function isIdentifier(string $value): bool
    {
        //if the variable starts with '$' then it must be an identifier
        if (!in_array($value, self::TOKEN_TABLE) && $value[0] == "$") {
            return true;
        }

        return false;
    }

    /**
     * Checks if the value is a string constant or numeric constant
     *
     * @param string $value
     * @return bool
     */
    public function isConstant(string $value): bool
    {
        //if the variable starts with " then it must be a string constant or if it is numeric it must be a numeric constant
        if (!in_array($value, self::TOKEN_TABLE) && (is_numeric($value) || $value[0] == "\"")) {
            return true;
        }

        return false;
    }

    /**
     * The method generates a file with the content of the ST array
     *
     * @param resource $symbolTableFile
     * @return void
     */
    public function getSymbolTableFile($symbolTableFile): void
    {
        foreach ($this->symbolTable as $key => $value) {
            fputcsv($symbolTableFile, [$key, $value], '|');
        }
    }

    /**
     * The method generates a file with the content of the PIF array
     *
     * @param resource $programInternalFormFile
     * @return void
     */
    public function getProgramInternalFormFile($programInternalFormFile): void
    {
        foreach ($this->input as $value) {
            if ($this->isIdentifier($value)) {
                fputcsv($programInternalFormFile, [$value, 0, $this->getSymbolTableValue($value)], '|');
                continue;
            }

            if ($this->isConstant($value)) {
                fputcsv($programInternalFormFile, [$value, 1, $this->getSymbolTableValue($value)], '|');
                continue;
            }

            fputcsv($programInternalFormFile, [$value, $this->getTokenTableValue($value), $this->getSymbolTableValue($value)], '|');
        }
    }

    /**
     * @param resource $errorTableFile
     * @param resource $file
     * @return void
     */
    public function getErrorFile($errorTableFile, $file): void
    {
        foreach ($this->errorTable as $word) {
            fputcsv($errorTableFile, [$word]);
        }
    }
}
