<?php

namespace Vanier\Api\Models;

class AppearancesModel extends BaseModel
{
    private $table_name = "pets_appearance";

    public function __construct(){
        parent::__construct();
    }

      // create appearance in the db
      public function createAppearance(array $appearance){
        return $this->insert($this->table_name, $appearance);
    }

    // update category
    public function updateAppearance(int $appearance_id, array $appearance){
        return $this->update($this->table_name, $appearance, ["appearance_id" => $appearance_id]);
    }
  
    // delete category
    public function deleteAppearance(int $appearance_id){
        return $this->delete($this->table_name, ["appearance_id" => $appearance_id]);
    }
    // get category by id
    public function getAppearanceId($appearance_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE appearance_id = :appearance_id";
        $query_value[":appearance_id"] = $appearance_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT a.*
                FROM $this->table_name AS a
                WHERE 1";

        // breed filter
        if (isset($filters["fur"])) {
            $sql .= " AND a.fur LIKE :fur";
            $query_values[":fur"] = $filters["fur"]."%";
        }

        // color filter
        if (isset($filters["color"])) {
            $sql .= " AND a.color LIKE :color";
            $query_values[":color"] = $filters["color"]."%";
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }
}
