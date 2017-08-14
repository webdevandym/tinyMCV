<?php

namespace app\Models\ClassStore;

class QueryLauncher extends QueryModel
{
    private $method;
    private $transfVal;

    public function init(\app\Helper\Http\Request $request)
    {
        $this->method = $this->chkProp($request, 'method') ? $request->method : $request;
        $this->transfVal = $this->chkProp($request, 'data');
    }

    public function runVisiter()
    {
        if ($this->method instanceof \app\Helper\Http\Request) {
            foreach ($this->method as $key => $value) {
                $keyclr = !$this->chkProp($this->method->$key, 'method') ? $this->clr((string) $key) : $this->clr((string) $this->method->$key->method);

                if (!method_exists($this, $keyclr)) {
                    continue;
                }

                unset($load);
                $objectVal = $this->chkProp($this->method->$keyclr, 'data');

                // $this->log($keyclr, $objectVal);

                $this->method->$keyclr->val = $this->runQuery($keyclr, $objectVal);
            }

            return $this->db->close(function () { return $this->method; });
        }

        if (!method_exists($this, $this->method)) {
            die();
        }

        return  $this->runQuery($this->method, $this->transfVal, function () { $this->db->close(); });
    }

    protected function runQuery($method, $val, $callback = null)
    {
        $res = $this->{$method}($val);
        if (is_callable($callback)) {
            $callback();
        }

        return $res;
    }
}
