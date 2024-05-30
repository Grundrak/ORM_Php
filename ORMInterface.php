<?php
interface ORMInterface {

    public function create($data);
    public function update($data, $conditions);
    public function delete($tableName, $condition, $params);
    public function fetch($tableName, $condition, $param);
}