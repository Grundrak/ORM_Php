<?php
require_once "./Database.php";
require_once "./ORMInterface.php";


class ORM implements ORMInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $class = get_class($data);
        $this->db->createTable($class);

        $reflectionClass = new ReflectionClass($class);
        $tableAnnotation = $reflectionClass->getAttributes(Table::class)[0]->newInstance();
        $tableName = $tableAnnotation->name;

        $fields = [];
        $values = [];
        $placeholders = [];

        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isInitialized($data)) {
                $columnAnnotation = $property->getAttributes(Column::class)[0]->newInstance();
                $fields[] = $columnAnnotation->name;
                $value = $property->getValue($data);


                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                $values[] = $value;
                $placeholders[] = '?';
            }
        }

        $sql = "INSERT INTO $tableName (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
        $stmt->execute();
        echo '/n create succes .';
    }
    public function update($data, $conditions)
    {
        $class = get_class($data);
        $reflectionClass = new ReflectionClass($class);
        $tableAnnotation = $reflectionClass->getAttributes(Table::class)[0]->newInstance();
        $tableName = $tableAnnotation->name;

        $fields = [];
        $values = [];
        $placeholders = [];

        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isInitialized($data)) {
                $columnAnnotation = $property->getAttributes(Column::class)[0]->newInstance();
                $value = $property->getValue($data);

                if ($value instanceof DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                
                if (!$columnAnnotation->primary) {
                    $fields[] = $columnAnnotation->name . " = ?";
                    $values[] = $value;
                    $placeholders[] = 's'; 
                }
            }
        }

        $sql = "UPDATE $tableName SET " . implode(', ', $fields) . " WHERE " . $conditions;
        echo "$sql";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->bind_param(implode('', $placeholders), ...$values);
        $stmt->execute();
    }
    public function delete($tableName, $condition, $params)
    {
        if (empty($tableName)) {
            throw new Exception("Table name cannot be empty put Table name please .");
        }
    
        $sql = "DELETE FROM $tableName WHERE $condition";
        $stmt = $this->db->prepareStatement($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed for delete : " . $stmt->error);
        }
    
        
        $stmt->bind_param($params['types'], ...$params['values']);
    
        if (!$stmt->execute()) {
            throw new Exception("Execute failed to delete : " . $stmt->error);
        }
    
        echo " Deleted successfully.";
    }
    public function fetch($tableName, $condition, $param){
        if(empty($tableName)){
            throw new Exception(("Table name cannot be empty put Table name please ."));
        }
        $sql = "SELECT * FROM $tableName WHERE $condition";
        $stmt = $this->db->prepareStatement($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed to fecth : " . $stmt->error);
        }
        $stmt->bind_param($param['types'], ...$param['values']);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed to fetch : " . $stmt->error);
        }
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null; 
        }
    }
}
