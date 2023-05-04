<?php

namespace Vanier\Api\Models;

class RecordModel extends BaseModel
{
    private $table_name = "record";

    public function __construct(){
        parent::__construct();
    }
    // create record in the db
    public function createRecord(array $record){
        return $this->insert($this->table_name, $record);
    }

    // public function deleteRecord(int $record_id){
    //     return $this->delete($this->table_name, ["record_id" => $record_id]);
    // }

    public function getRecordId($record_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE record_id = :record_id";
        $query_value[":record_id"] = $record_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT r.*
                FROM $this->table_name AS r
                WHERE 1";

        // address filter
        if (isset($filters["address"])) {
            $sql .= " AND r.address LIKE :address";
            $query_values[":address"] = $filters["address"]."%";
        }

        // city filter
        if (isset($filters["city"])) {
            $sql .= " AND r.city LIKE :city";
            $query_values[":city"] = $filters["city"]."%";
        }

        // state filter
        if (isset($filters["state"])) {
            $sql .= " AND r.state LIKE :state";
            $query_values[":state"] = $filters["state"]."%";
        }

        // postal_code filter
        if (isset($filters["postal_code"])) {
            $sql .= " AND r.postal_code LIKE :postal_code";
            $query_values[":postal_code"] = $filters["postal_code"]."%";
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }
}
