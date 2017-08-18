<?php

namespace app\Models;

use app\Models\ClassStore\QueryLauncher;

class ModelQueryEdit extends QueryLauncher
{
    /**********************************************************************/
    /*************************addReport***********************************/
    /**********************************************************************/

    public function addReport($updval)
    {
        throw new \Exception(print_r($updval));
        foreach ($updval as $value) {
            if ($value instanceof \stdClass) {
                foreach ($value as $res) {
                    $val['date'][] = $res;
                }
            } else {
                $val[] = $this->clr($value);
            }
        }

        $val[3] = "'".$val[3]."'";
        $a = [];

        $DBColumns = 'object_id, jobtype_id, user_id, job_descr, job_hours,account_id,job_date';
        $DBColArr = explode(',', $DBColumns);
        $setStat = '';

        for ($i = 0; $i < count($DBColArr) - 1; ++$i) {
            $setStat .= $val[$i].',';
        }

        foreach ($val['date'] as $val) {
            $sql = "INSERT INTO  proj_jobs WITH (TABLOCKX) ($DBColumns) VALUES($setStat CONVERT(DATETIME,'$val'))";

            $this->returnQuery($sql);
        }
    }

    /**********************************************************************/
    /*************************UpDateDB***********************************/
    /**********************************************************************/

    public function UpDateDB($valStore)
    {
        foreach ($valStore as $value) {
            $val[] = $this->clr($value);
        }

        $specVal = explode('_', explode('it', $val[0])[1]);

        $DBColumns = ['object_id', 'jobtype_id', 'user_id', 'job_descr', 'job_date', 'job_hours'];
        $setStat = '';

        for ($i = 1; $i < count($val); ++$i) {
            $setStat .= $DBColumns[$i - 1]."='".$val[$i]."',";
        }

        $setStat = substr($setStat, 0, -1);

        $sql = "UPDATE proj_jobs WITH (TABLOCKX) SET $setStat WHERE id = '$specVal[0]'";

        $this->returnQuery($sql);
    }

    /**********************************************************************/
    /*************************removeRecord***********************************/
    /**********************************************************************/

    public function removeRecord($recToDel)
    {
        $id = $this->chkProp($recToDel, 'rec');

        $sql = "DELETE FROM proj_jobs WITH (TABLOCKX) WHERE id IN ($id)";

        $this->returnQuery($sql);
    }
}
