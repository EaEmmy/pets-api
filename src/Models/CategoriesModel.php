<?php

namespace Vanier\Api\Models;

class CategoriesModel extends BaseModel
{
    private $table_name = "category";

    public function __construct(){
        parent::__construct();
    }

      // create category in the db
      public function createCategory(array $category){
        return $this->insert($this->table_name, $category);
    }

    // update category
    public function updateCategory(int $category_id, array $category){
        return $this->update($this->table_name, $category, ["category_id" => $category_id]);
    }
  
    // delete category
    public function deleteCategory(int $category_id){
        return $this->delete($this->table_name, ["category_id" => $category_id]);
    }
    // get category by id
    public function getCategoryId($category_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE category_id = :category_id";
        $query_value[":category_id"] = $category_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    public function getAll(array $filters = []) {
        $query_values = [];

        $sql = "SELECT c.*
                FROM $this->table_name AS c
                WHERE 1";

        // name filter
        if (isset($filters["name"])) {
            $sql .= " AND c.name LIKE :name";
            $query_values[":name"] = $filters["name"]."%";
        }
        
        //return $this->run($sql, $query_values)->fetchAll();
        return $this->paginate($sql, $query_values);
    }
}
