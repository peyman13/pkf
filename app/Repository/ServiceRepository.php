<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class ServiceRepository
{
    private $db = "services";


    public function insertSteps($data)
    {
        return json_encode(DB::table($this->db)
            ->insertGetId($data));
    }

    public function updateState($id, $data)
    {
        return DB::table($this->db)
            ->where('id', $id)
            ->update($data);
    }
}
