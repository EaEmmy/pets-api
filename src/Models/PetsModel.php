<?php

namespace Vanier\Api\Models;

/**
 * Summary of PetsModel
 */
class PetsModel extends BaseModel
{
    /**
     * Summary of table_name
     * @var string
     */
    private $table_name = "pets_info";

    /**
     * Summary of __construct
     */
    public function __construct(){
        parent::__construct();
    }
    /**
     * Summary of createPet
     * @param array $pet
     * @return bool|string
     */
    public function createPet(array $pet){
        return $this->insert($this->table_name, $pet);
    }
    /**
     * Summary of updatePet
     * @param array $pet
     * @param int $animal_id
     * @return mixed
     */
    public function updatePet(array $pet, int $animal_id){
        //var_dump($animal_id);exit;
        return $this->update($this->table_name, $pet,["animal_id" => $animal_id]);
    }
    /**
     * Summary of deletePet
     * @param int $animal_id
     * @return mixed
     */
    public function deletePet(int $animal_id){
        return $this->delete($this->table_name, ["animal_id" => $animal_id]);
    }

    /**
     * Summary of getPetId
     * @param mixed $animal_id
     * @return mixed
     */
    public function getPetId($animal_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE animal_id = :animal_id";
        $query_value[":animal_id"] = $animal_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    /**
     * Summary of getAll
     * @param array $filters
     * @return array
     */
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

    /**
     * Summary of getPetsByCategoryId
     * @param mixed $category_id
     * @return array
     */
    public function getPetsByCategoryId($category_id)
    {
        $sql = "SELECT * FROM  $this->table_name WHERE breed_id IN
        (SELECT breed_id FROM breed WHERE category_id = :category_id)";

        $filters_value[":category_id"] = $category_id;
        //return $this->run($sql, $filters_value)->fetch();
        return $this->paginate($sql, $filters_value);
    }

    /**
     * Summary of getPetsByEntryId
     * @param mixed $entry_id
     * @return array
     */
    public function getPetsByEntryId($entry_id)
    {
        $sql = "SELECT * FROM  $this->table_name WHERE record_id IN
        (SELECT record_id FROM record WHERE entry_id = :entry_id)";

        $filters_value[":entry_id"] = $entry_id;
        //return $this->run($sql, $filters_value)->fetch();
        return $this->paginate($sql, $filters_value);
    }

}
