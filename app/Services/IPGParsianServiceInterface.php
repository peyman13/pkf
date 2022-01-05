<?php

namespace App\Services;

interface IPGParsianServiceInterface
{
    public function getTransaction(string $iban, string $nationalCode, string $birthDate);
    // public function sendToGateWay(string $iban, string $nationalCode, string $birthDate);
    // public function getVerify(string $iban, string $nationalCode, string $birthDate);
    // public function _genHMAC();
}
