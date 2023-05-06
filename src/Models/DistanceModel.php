<?php
namespace Vanier\Api\Models;

use Vanier\Api\Models\BaseModel;

/**
 * Summary of DistanceModel
 */
class DistanceModel extends BaseModel{

    /**
     * Summary of getCodeCoordinates
     * @param mixed $code
     * @return mixed
     */

    public function getCodeCoordinates($code)
    {
        // var_dump($code);exit;
        $sql = "SELECT latitude, longitude FROM ca_codes
        WHERE postal_code = :postal_code";

        return $this->run($sql, [":postal_code" => $code])->fetch();
    }
}