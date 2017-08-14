<?php

namespace app\Models;

use app\Helper\Database\messStore;
use app\Helper\Users\User;
use app\Models\ClassStore\QueryLauncher;

class ModelQueryGet extends QueryLauncher
{
    /**********************************************************************/
    /*************************getJobType***********************************/
    /**********************************************************************/

    public function getJobType($selector)
    {
        $select = $this->chkProp($selector, 'select');
        $a = [];
        $result = $this->cacheData(24 * 60 * 60, 'SELECT name,id FROM proj_jobtypes ORDER BY id', $select);

        foreach ($result as $row) {
            $a[] .= '<option '.((!empty($select) && $select === $row->name) ? ' selected="selected"' : '')." value='".$row->id."'>".$row->name.'</option>';
        }

        return $a;
    }

    /**********************************************************************/
    /*************************getObjectName***********************************/
    /**********************************************************************/

    public function getObjectName($valObj)
    {
        list($name, $type) = $this->chkProp($valObj, ['name', 'type']);

        $type = ((int) $type === 5) ? 'ALL' : $type;

        $sql = "EXEC pr_ObjName @type = '$type', @name = '$name'";

        $result = $this->cacheData(24 * 60 * 60, $sql, $name.$type);

        $a = $select = $b = '';

        foreach ($result as $row) {
            if (strtolower($row->object_num) === '-none-') {
                $b .= "<option value='".$row->id."'>".$row->object_num.'</option>';
            } else {
                $a .= "<option value='".$row->id."'>".$row->object_num.'</option>';
            }
        }

        return $b.$a;
    }

    /**********************************************************************/
    /*************************getUserName**********************************/
    /**********************************************************************/
    protected function getUserName($switch)
    {
        User::checkSESSION();

        $switcher = $this->chkProp($switch, 'edit');

        $sql = 'SELECT f_name,name,id FROM proj_users WHERE disabled = 0 order by f_name';
        $result = $this->cacheData(24 * 60 * 60, $sql, $switcher);

        $showName = !empty($switcher) ? 'name' : 'f_name';
        $getTask = !empty($switcher) ? $switch : $_SESSION['user'];

        $a = [];

        foreach ($result as $row) {
            $a[] = '<option'.($getTask === $row->name ? " selected = 'selected' " : '')." value = '".$row->name."' idnum = '".$row->id."'>".$row->$showName.'</option>';
        }

        return $a;
    }

    protected function getObjectType($val)
    {
        $result = $this->cacheData(24 * 60 * 60, 'SELECT name,id FROM proj_objtypes order by id');
        $i = 0;
        foreach ($result as $row) {
            if ((int) $row->id !== 5) {
                $a[$i++] = "<option value = '".$row->id."'>".$row->name.'</option>';
            }
        }

        asort($a);

        return $a;
    }

    /**********************************************************************/
    /*************************getProject***********************************/
    /**********************************************************************/

    protected function getProject($query)
    {
        list($q, $switch) = $this->chkProp($query, ['query', 'switch']);

        // $id = 'name';
        $a = [];
        $first = false;

        $id = ($q === 'Customer') ? 'id' : 'name';
        $sql = ($q === 'Customer') ? 'SELECT distinct name, id FROM proj_customers ORDER BY name' : "SELECT name,id FROM proj_names WHERE customer_id = '$q' ORDER BY name";

        $result = $this->cacheData(60 * 60, $sql, $q.$switch);

        foreach ($result as  $row) {
            // print_r($row);
            if ((!$first && !$switch) || ($switch === $row->name)) {
                $a[] = "<option selected='selected' value = '".$row->{$id}."'".'>'.$row->name.'</option>';
                $first = true;
                $switch = false;
            } else {
                $a[] = "<option value = '".$row->{$id}."'".'>'.$row->name.'</option>';
            }
        }

        return $a;
    }

    /**********************************************************************/
    /*************************getReportWeek***********************************/
    /**********************************************************************/
    protected function getReportWeek($userInfo)
    {
        list($name, $startDate, $endDate) = $this->chkProp($userInfo, ['name', 'startDate', 'endDate']);

        $retDate = $name.','.$startDate.','.$endDate;

        $a = ['name', 'userName', 'account', 'descr', 'jobType', 'dateJob', 'hoursJob'];
        $out = $table = $result = '';

        /*all query in json file: see location in parent class"*/

        $sql = "EXEC pr_Report @name = '$name',@startDate = '$startDate', @endDate = '$endDate'";

        $result = $this->returnQuery($sql);

        $out .= "<div class='container'>";

        foreach ($result as $row) {
            $table .= <<<_END
	<tr id = "it{$row->id}_{$row->object_id}">
  <td colspan = '2' class = 'chekerDelete' delrec = ''><i class="fa fa-chevron-down " aria-hidden="true"></i></td>
	<td class = 'editRow' data-toggle="modal" data-target="#editModal" onclick = "insertData(this)"><i class="fa fa-pencil" aria-hidden="true"></i></td>
	<td class = 'deleteRow' data-toggle="modal" data-target="#deleteModal" onclick = "getTDID(this);"><i class="fa fa-times" aria-hidden="true"></i></td>
_END;

            foreach ($a as $item) {
                if ($item === 'hoursJob') {
                    $row->$item = (float) $row->$item;
                }
                if ($item === 'name') {
                    $table .= "<td class = '$item'>".str_replace('/ /', '', $row->$item)."<br><span id = 'custStyle'>".$row->customer.'</span></td>';
                } else {
                    $table .= "<td class = '$item'>".$row->$item.'</td>';
                }
            }
            $table .= '</tr>';
        }

        if ($table) {
            if (empty($title)) {
                $linkDomText = __ROOT__.'/app/Views/templates/langContent/tsDOMText';
                $title = messStore::genLinks('reportTitle', $linkDomText, true);
            }

            $out .= <<<_END
<table class="table table-hover" id = "tableReport">
    <thead>
      <tr style="font-weight: bold;">
      <th></th>
      <th></th>
        <th attr = "name">$title->proj</th>
        <th style = 'text-align: center;' attr = "userName">$title->pname</th>
        <th attr = "account">$title->acc</th>
        <th style = "width: 30%;" attr = "descr">$title->desc</th>
        <th attr = "jobType">$title->jtype</th>
        <th attr = "dateJob">$title->jdate</th>
        <th style = 'text-align: center;' attr = "hoursJob">$title->jhour</th>
      </tr>
    </thead>
    <tbody>
_END;

            $table .= '</tbody></table>';

            $out .= $table;
        } else {
            return "<div id = 'noRecords' class ='alert alert-success'><span>No Records</span></div></div>";
        }

        return $out.<<<_END
	</div>

	<script>
		tableSortAlg();
		tableMassDeletePicker();
		function returnDate() {
    		return "$retDate";
		};
	</script>
_END;
    }

    /**********************************************************************/
    /*************************getLastReport***********************************/
    /**********************************************************************/

    protected function getLastReport($val)
    {
        $user = $this->chkProp($val, 'name');

        $query = "SELECT max(distinct job_date) as maxdate
              			FROM proj_jobs
              			WHERE user_id IN (
              						SELECT id FROM proj_users WHERE name = '$user')";

        $result = $this->returnQuery($query);

        return reset($result)->maxdate;
    }
}
