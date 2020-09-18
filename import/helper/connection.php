<?php

if (php_sapi_name() == 'cli') {
    // include_once '../../config.php';
}

class db
{
    public $connection = null;
    public $query = null;
    public static $instance = null;

    public function __construct($data = [])
    {
        $username = isset($data['DB_USERNAME']) ? $data['DB_USERNAME'] : DB_USERNAME;
        $password = isset($data['DB_PASSWORD']) ? $data['DB_PASSWORD'] : DB_PASSWORD;
        $host = isset($data['DB_HOSTNAME']) ? $data['DB_HOSTNAME'] : DB_HOSTNAME;
        $db = isset($data['DB_DATABASE']) ? $data['DB_DATABASE'] : DB_DATABASE;
        try {
            if ($this->connection == null) {
                $options = array(
                    \PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                );

                $this->connection = new \PDO("mysql:dbname=$db;host=$host", $username, $password, $options);

                $this->connection->exec('set names utf8');
            }
        
            return $this;
        } catch (\PDOException $e) {
            exit('Error: '.$e->getMessage()."\n");
        }
        return $this;
    }

    public static function gi($data)
    {
        $db = isset($data['DB_DATABASE']) ? $data['DB_DATABASE'] : DB_DATABASE;
        if (!isset(self::$instance[$db])) {
            self::$instance[$db] = new db($data);
        }

        return self::$instance[$db];
    }

    public function getLastid()
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function query($sql, $values = [])
    {
        try {
            $this->query = $this->connection->prepare($sql);
            if (is_array($values)) {
                $this->query->execute(array_values($values));
            } else {
                $this->query->execute();
            }

            return $this;
        } catch (PDOException $e) {
            echo $sql;
            echo 'Error: '.$e->getMessage();
            var_dump($sql, $values);
        }

        return false;
    }

    public function select($sql, $where = [])
    {
        $this->query = $this->connection->prepare($sql);
        $this->query->execute(array_values($where));

        return $this;
    }

    public function first($column = '*')
    {
        $all = $this->query->fetch(PDO::FETCH_ASSOC);

        if (is_array($column) || ($column != '*' && is_string($column))) {
            if (is_string($column)) {
                $column = explode(',', $column);
            }
            if (is_array($all)) {
                return array_filter($all, function ($key) use ($column) {
                    return in_array($key, $column);
                }, ARRAY_FILTER_USE_KEY);
            }
        }

        return $all;
    }

    public function get()
    {
        return $this->query->fetchall(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        return $this->query->rowCount();
    }
}

function db($data = [])
{
    return db::gi($data);
}
function dd()
{
    var_dump(func_get_args());
    exit;
}