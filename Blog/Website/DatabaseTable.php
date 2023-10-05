<?php

namespace Website;
class DatabaseTable
{
    public function __construct(
        private \PDO $pdo,
        private string $table,
        private string $primaryKey,
        private string $className = '\stdClass',
        private array $constructorArgs = []
    ){
    }

    public function insert($values)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';
        $dataKeys = [];
        foreach ($values as $key => $value) {
            $dataKeys[] = $key;
        }

        $query .= '`' . implode('`, `', $dataKeys) . '`)';
        $query .= ' VALUES (:' . implode(', :', $dataKeys) . ')';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);

        return $this->pdo->lastInsertId();
    }


    private function update($values)
    {
        $query = ' UPDATE `' . $this->table . '` SET ';
        foreach ($values as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        // Set the :primaryKey variable
        $values['primaryKey'] = $values['id'];

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);

    }

    public function findById($value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->rimaryKey . '` = :value';
        $values = [
            'value' => $value
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetch();
    }

    function find(string $field, string $value, string $orderBy = null, int $limit = 0, int $offset=0)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = :value';

        if ($orderBy != null){
            $query .= ' ORDER BY ' . $orderBy;
        }
        if ($limit > 0){
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset > 0) {
            $query .= ' OFFSET  ' . $offset;
        }

        $stmt = $this->pdo->prepare($query);
        $values = [
            'value' => $value
        ];

        $stmt->execute($values);
        return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
    }

    public function total(string $field = null, string $value = null)
    {
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '`';
        $parameters = [];
        if (!empty($field)){
            $query .= ' WHERE `' . $field . '` = :value';
            $parameters = ['value' => $value];
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($parameters);
        $row = $stmt->fetch();
        return $row[0];
    }

    public function findAll($orderBy = null, int $limit = 0, int $offset = 0)
    {
        $query = 'SELECT * FROM `' . $this->table . '`';
        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($limit > 0 ) {
            $query .= ' LIMIT  ' . $limit;
        }

        if ($offset > 0) {
            $query .= ' OFFSET  ' . $offset;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
    }

    public function save($record)
    {
        // create new instance of className
        $entity = new $this->className(...$this->constructorArgs);
        try {
            if (empty($record[$this->primaryKey])) {
                unset($record[$this->primaryKey]);
            };
            $entity->{$this->primaryKey} = $this->insert($record);
        } catch (\PDOException $exception) {
            // catch the error due to duplicate key -> update, not insert
            $this->update($record);
        }

        // copy data from record to the new instance
        foreach ($record as $key => $value){
            if(!empty($value)){
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }
                $entity->$key = $value;
            }
        }
        return $entity;
    }

    public function delete($field, $value) {
        $values = [':value' => $value];
        $stmt = $this->pdo->prepare('DELETE FROM `' . $this->table . '` WHERE `' . $field . '` = :value');
        $stmt->execute($values);
    }
}