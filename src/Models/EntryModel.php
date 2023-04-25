<?php

namespace Vanier\Api\Models;

class EntryModel extends BaseModel
{
    private $table_name = "entry";

    public function __construct(){
        parent::__construct();
    }
    // create entry in the db
    public function createEntry(array $entry){
        return $this->insert($this->table_name, $entry);
    }

    public function getEntryId($entry_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE entry_id = :entry_id";
        $query_value[":entry_id"] = $entry_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }
    // public function deleteEntry(int $animal_id){
    //     return $this->delete($this->table_name, ["animal_id" => $animal_id]);
    // }

    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT e.*
                FROM $this->table_name AS e
                WHERE 1";

        // date filter
        if (isset($filters["date"])) {
            $sql .= " AND e.date LIKE :date";
            $query_values[":date"] = $filters["date"]."%";
        }

        // datetype filter
        if (isset($filters["date_type"])) {
            $sql .= " AND e.date_type LIKE :date_type";
            $query_values[":date_type"] = $filters["date_type"]."%";
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }
}
