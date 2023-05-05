<?php
namespace Vanier\Api\Models;

use Vanier\Api\Models\BaseModel;

<<<<<<< HEAD
/**
 * Summary of DistanceModel
 */
class DistanceModel extends BaseModel{

    /**
     * Summary of getCodeCoordinates
     * @param mixed $code
     * @return mixed
     */
=======
class DistanceModel extends BaseModel{

>>>>>>> 8ad8cc0cfca5444ed2136984858bc0e990e2e674
    public function getCodeCoordinates($code)
    {
        // var_dump($code);exit;
        $sql = "SELECT latitude, longitude FROM ca_codes
        WHERE postal_code = :postal_code";

        return $this->run($sql, [":postal_code" => $code])->fetch();
    }
}