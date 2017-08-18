<?php

define('__ROOT__', __DIR__);

$loader = require_once __ROOT__.'/vendor/autoload.php';
$loader->addPsr4('app\\', __ROOT__.'/app/');

$dotenv = new \Dotenv\Dotenv(__ROOT__);
$dotenv->load();

use app\Helper\Database\DB;

$conn = DB::getInst()->conn();

$sql = "SELECT distinct ob.id, ob.object_num, ob.objtype_id, st.status
          FROM proj_objects  ob , proj_assigns a, proj_status st
            WHERE ob.id = a.object_id
              and ob.proj_id in (SELECT id FROM proj_names WHERE name = 'RVT-102-2001')
              -- and a.user_id in (SELECT distinct id FROM proj_users WHERE name != 'tedgik')
              and ob.status_id = st.id";

$res = $conn->queryMysql($sql);

echo '<pre>';
print_r($res->fetchAll(\PDO::FETCH_OBJ));

echo '</pre>';
