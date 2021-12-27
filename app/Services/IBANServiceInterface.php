<?php

namespace App\Services;

interface IBANServiceInterface
{
    public function isValidIBAN(string $iban, string $nationalCode, string $birthDate);
}
