<?php

namespace app\Models\ClassStore;

class QueryLauncher extends QueryModel
{
    private $method;
    private $transfVal;

    public function init(\app\Helper\Http\Request $request)
    {
        $m = json_decode($request->method);
        $this->method = is_object($m) ? $this->clr($m) : $request->method;
        $this->transfVal = $request->val;
        // throw new \Exception(print_r($request));
    }

    public function runVisiter()
    {
        if ($this->method instanceof stdClass) {
            foreach ($this->method as $key => $value) {
                $keyclr = $this->clr((string) $key);

                if (!method_exists($this, $keyclr)) {
                    continue;
                }

                unset($load);
                $objectVal = $this->chkProp($this->method->$keyclr, 'obj');

                // $this->log($keyclr, $objectVal);

                $this->method->$keyclr->val = $this->runQuery($keyclr, $objectVal);
            }

            return $this->db->close($this->method);
        }

        if (!method_exists($this, $this->method)) {
            return $this->db->close();
        }

        // if (is_array($res)) {
        //     foreach ($res as $key => $value) {
        //         echo $value;
        //     }
        // }

        $this->runQuery($this->method, $this->transfVal);
        $this->db->close();
        throw new \Exception(print_r($this->db));
    }

    protected function runQuery($method, $val)
    {
        $res = $this->{$method}($val);

        return $res;
    }
}
