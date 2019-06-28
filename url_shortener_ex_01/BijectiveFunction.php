<?php

/*
*   An implementation of the bijective function 
*   To get unique non-recurring short urls
*   Original idea was from this post on stackoverflow 
*   https://stackoverflow.com/questions/742013/how-do-i-create-a-url-shortener
*
*   Author: Abdul-Harisu Inusah
*   Email:  inusahharis@gmail.com
*/

class BJFunction {
    private $keys;

    public function __construct(string $keys = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789")
    {
        $this->keys = str_split($keys);
    }

    public function encode(int $id) : string {
        $keysLength = count($this->keys);
        $encodedData = [];
        while ($id >= $keysLength) {
            $encodedValue = $id % $keysLength;
            $id = intdiv($id, $keysLength);
            array_push($encodedData,$this->keys[$encodedValue]);
        }
        array_push($encodedData,$this->keys[$id]);
        return strrev(implode("",$encodedData));
    }

    public function decode(string $encodedData) : int {
        $decodedData = [];
        $encodedData = str_split($encodedData);
        $flippedKeys = array_flip($this->keys);
        $encodedDataLength = count($encodedData);
        for ($i = $encodedDataLength - 1; $i >= 0; $i--) {
            $num = $flippedKeys[$encodedData[$i]];
            $decodedValue = ($num * (62 ** (($encodedDataLength - 1) - $i)));
            array_push($decodedData,$decodedValue);
        }
        return array_sum($decodedData); // decodedId
    }
}