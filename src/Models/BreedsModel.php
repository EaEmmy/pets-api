<?php

namespace Vanier\Api\Models;

class BreedsModel extends BaseModel
{
    private $table_name = "breed";

    public function __construct(){
        parent::__construct();
    }
   
    public function createBreed(array $breed){
        return $this->insert($this->table_name, $breed);
    }

    public function updateBreed(int $breed_id, array $breed){
        return $this->update($this->table_name, $breed, ["breed_id" => $breed_id]);
    }
  
    
    public function deleteBreed(int $breed_id){
        return $this->delete($this->table_name, ["breed_id" => $breed_id]);
    }
 
    public function getBreedId($breed_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE breed_id = :breed_id";
        $query_value[":breed_id"] = $breed_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }


    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT b.*
                FROM $this->table_name AS b
                WHERE 1";

        // name filter
        if (isset($filters["breed_name"])) {
            $sql .= " AND b.breed_name LIKE :breed_name";
            $query_values[":breed_name"] = "%".$filters["breed_name"]."%";
        }
        
        // age filter
        if (isset($filters["origin"])) {
            $sql .= " AND b.origin = :origin";
            $query_values[":origin"] = $filters["origin"]."%";
        }
        
        // gender filter
        if (isset($filters["coat"])) {
            $sql .= " AND b.coat = :coat";
            $query_values[":coat"] = "%".$filters["coat"]."%";
        }
        
        // status filter
        if (isset($filters["type"])) {
            $sql .= " AND b.type LIKE :type";
            $query_values[":type"] = "%".$filters["type"]."%";
        }

        if (isset($filters["lifespan"])) {
            $sql .= " AND b.lifespan LIKE :lifespan";
            $query_values[":lifespan"] = $filters["lifespan"];
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }
}

