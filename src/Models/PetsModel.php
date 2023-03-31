<?php

namespace Vanier\Api\Models;

class PetsModel extends BaseModel
{
    private $table_name = "pets_info";

    public function __construct(){
        parent::__construct();
    }

      public function createPet(array $pet){
        return $this->insert($this->table_name, $pet);
    }

    public function updatePet(int $animal_id, array $pet){
        return $this->update($this->table_name, $pet, ["animal_id" => $animal_id]);
    }
  
    public function deletePet(int $animal_id){
        return $this->delete($this->table_name, ["animal_id" => $animal_id]);
    }

    public function getPetId($animal_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE animal_id = :animal_id";
        $query_value[":animal_id"] = $animal_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT p.*
                FROM $this->table_name AS p
                WHERE 1";

        // name filter
        if (isset($filters["name"])) {
            $sql .= " AND p.name LIKE :name";
            $query_values[":name"] = $filters["name"]."%";
        }
        
        // age filter
        if (isset($filters["age"])) {
            $sql .= " AND p.age = :age";
            $query_values[":age"] = $filters["age"];
        }
        
        // gender filter
        if (isset($filters["gender"])) {
            $sql .= " AND p.gender = :gender";
            $query_values[":gender"] = $filters["gender"];
        }
        
        // status filter
        if (isset($filters["status"])) {
            $sql .= " AND p.status LIKE :status";
            $query_values[":status"] = "%".$filters["status"]."%";
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }

    public function getPetsByCategoryId($category_id)
    {
        $sql = "SELECT * FROM  $this->table_name WHERE breed_id IN
        (SELECT breed_id FROM breed WHERE category_id = :category_id)";

        $filters_value[":category_id"] = $category_id;
        //return $this->run($sql, $filters_value)->fetch();
        return $this->paginate($sql, $filters_value);
    }

    public function getPetsByEntryId($entry_id)
    {
        $sql = "SELECT * FROM  $this->table_name WHERE record_id IN
        (SELECT record_id FROM record WHERE entry_id = :entry_id)";

        $filters_value[":entry_id"] = $entry_id;
        //return $this->run($sql, $filters_value)->fetch();
        return $this->paginate($sql, $filters_value);
    }

}
