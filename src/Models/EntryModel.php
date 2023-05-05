<?php

namespace Vanier\Api\Models;

/**
 * Summary of EntryModel
 */
class EntryModel extends BaseModel
{
    /**
     * Summary of table_name
     * @var string
     */
    private $table_name = "entry";

    /**
     * Summary of __construct
     */
    public function __construct(){
        parent::__construct();
    }
    // create entry in the db
    /**
     * Summary of createEntry
     * @param array $entry
     * @return bool|string
     */
    public function createEntry(array $entry){
        return $this->insert($this->table_name, $entry);
    }
    // public function deleteEntry(int $entry_id){
    //     return $this->delete($this->table_name, ["entry_id" => $entry_id]);
    // }   

    /**
     * Summary of getEntryId
     * @param mixed $entry_id
     * @return mixed
     */
    public function getEntryId($entry_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE entry_id = :entry_id";
        $query_value[":entry_id"] = $entry_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }
   
    /**
     * Summary of getAll
     * @param array $filters
     * @return array
     */
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
