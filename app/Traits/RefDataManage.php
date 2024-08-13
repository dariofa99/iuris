<?php
namespace App\Traits;
/**
 * 
 */
trait RefDataManage
{
    public function getDataVal($ref_id, $ref_option)
    {
        $ref_data = $this->aditional_data()
            ->where([
                'reference_data_id' => $ref_id,
                'reference_data_option_id' => $ref_option
            ])->first();

        if ($ref_data) {
            return $ref_data;
        }
        return false;
    }
}