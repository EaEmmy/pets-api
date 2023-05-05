<?php

namespace Vanier\Api\Models;

/**
 * Summary of CategoriesModel
 */
class CategoriesModel extends BaseModel
{
    /**
     * Summary of table_name
     * @var string
     */
    private $table_name = "category";

    /**
     * Summary of __construct
     */
    public function __construct(){
        parent::__construct();
    }

      // create category in the db
      /**
       * Summary of createCategory
       * @param array $category
       * @return bool|string
       */
      public function createCategory(array $category){
        return $this->insert($this->table_name, $category);
    }

    // update category
    /**
     * Summary of updateCategory
     * @param int $category_id
     * @param array $category
     * @return mixed
     */
    public function updateCategory(int $category_id, array $category){
        return $this->update($this->table_name, $category, ["category_id" => $category_id]);
    }
  
    // delete category
    /**
     * Summary of deleteCategory
     * @param int $category_id
     * @return mixed
     */
    public function deleteCategory(int $category_id){
        return $this->delete($this->table_name, ["category_id" => $category_id]);
    }
    // get category by id
    /**
     * Summary of getCategoryId
     * @param mixed $category_id
     * @return mixed
     */
    public function getCategoryId($category_id)
    {
        $sql = " SELECT * FROM $this->table_name WHERE category_id = :category_id";
        $query_value[":category_id"] = $category_id; 
        return $this->run($sql, $query_value)->fetchAll();
    }

    /**
     * Summary of getAll
     * @param array $filters
     * @return array
     */
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
