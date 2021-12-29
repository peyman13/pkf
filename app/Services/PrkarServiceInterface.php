<?php

namespace App\Services;

interface PrkarServiceInterface
{
    public function getProvince();
    public function getCompetency();
    public function getCity($id);
    public function getMunicipality($id);
    public function setEmployee($data);
}
