<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 15/08/2018
 * Time: 16:06
 */

namespace App\Model;


class PPTConvertModel
{
    private $ppt;

    /**
     * @return mixed
     */
    public function getPpt()
    {
        return $this->ppt;
    }

    /**
     * @param mixed $ppt
     * @return PPTConvertModel
     */
    public function setPpt($ppt)
    {
        $this->ppt = $ppt;
        return $this;
    }


}