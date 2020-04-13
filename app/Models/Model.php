<?php


namespace app\Models;

use config\Config;
use PDO;
use PDOException;
use app\Helpers;
class Model
{
    protected $c = "";
    public function __construct()
    {
        $db = Config::get('db');
        $username = Config::get('username');
        $password = Config::get('password');
        $host = Config::get('servername');
        try {
            //Create connection for DBH.
            $this->c = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            $this->c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            die("Connection failed: " . $e->getMessage() .". Please use Model.php in app/Models to change settings.");
        }
    }

    /**
     * @param $table - Table used to define where data will come from
     * @param $selection - String for selection
     * @param $conditions - String for conditions (Not required)
     * @param $data - Data for where condition (Not required)
     * @param $join_table - String naming join table (Not required)
     * @param $join_conditions -  ON conditions for join (Not required)
     * @param $limit - To limit data pulled, helpful for pagination (Not required)
     * @param $offset - To offset data, helpful for pagination (Not required)
     * @param $distinct - Distinct data (not required)
     * @param $groupby - Group by a string (not required)
     * @return array - Data from successful query
     */
    public function selectRows($table, $selection, $conditions = null, $data = null, $join_table = null, $join_conditions = null, $limit = null, $offset = null, $distinct = false, $group_by = null, $order_by = null){
        /*
         * We could probably make the string a bit neater, i.e. loop through, create string with :$col etc. Didn't want to spend too much time on it.
         * WHERE col = :col, col2 = :col2
         * All data must be placed accordingly without : in the $data array.
         */
        try {
            if ($distinct){
                $query = "SELECT DISTINCT $selection FROM $table";
            }
            else{
                $query = "SELECT $selection FROM $table";
            }

            //Build a meaningful query
            if ($join_table != null){
                if ($join_conditions == null){
                    die("A join won't work without valid join conditions. Please update your selectRows query.");
                }
                //This means there are multiple entries we must append.
                if (is_array($join_table)){
                    if (count($join_table) != count($join_conditions)){
                        die("Join table & join conditions are not equal - please make sure their counts are the same");
                    }
                    $count = 0;
                    foreach($join_table as $join){
                        $query .= " JOIN $join";
                        $query .= " ON ". $join_conditions[$count];
                        $count++;
                    }
                }
                else{
                    $query .= " JOIN $join_table";
                    $query .= " ON $join_conditions";
                }
            }
            if ($conditions != null){
                $query .= " WHERE $conditions";
            }
            if ($group_by != null){
                $query .= " GROUP BY $group_by";
            }
            if ($order_by != null){
                $query .= " ORDER BY $order_by";
            }
            if ($limit != null){
                $query .= " LIMIT $limit";
            }
            if ($offset != null){
                $query .= " OFFSET $offset";
            }
            $stmt = $this->c->prepare($query);
            //Only execute with data if it exists
            if ($data != null) {
                $stmt->execute($data);
            }
            else{
                $stmt->execute();
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        catch (PDOException $e){
            die($e->getMessage(). " (selectRows in Model.php on $table)");
        }
    }


    /**
     * @param $table - Table used to define where to insert data
     * @param $columns - String used to parse values
     * @param $data - Data to insert
     */
    public function add($table, $columns, $data){
        //Remove whitespaces to make it easier to process the string.
        $columns_clean = Helpers::removeWhitespaces($columns);
        //This string will be used for the insert in the columns section.
        $values_string = ":".str_replace(",", ",:", $columns_clean);
        $query = "INSERT INTO $table ($columns_clean) VALUES ($values_string)";
        try {
            $statement = $this->c->prepare($query);
            $statement->execute($data);
            return $this->c->lastInsertId();
        }
        catch (PDOException $e){
            die($e->getMessage(). " (add in Model.php on $table query: $query) ");
        }
    }

    /**
     * @param $table - Table used to define where to update data
     * @param $columns - String used to parse values
     * @param $data - Data to update, and where conditions
     * @param $condition - Condition to set
     */
    public function update($table, $columns, $data, $condition){
        /*
         * We could probably make the string a bit neater, i.e. loop through, create string with :$col etc. Didn't want to spend too much time on it.
         * SET col = :col, col2 = :col2
         * WHERE col = :col, col2 = :col2
         * All data must be placed accordingly without : in the $data array.
         */
        try {
            $statement = $this->c->prepare("UPDATE $table SET $columns WHERE $condition");
            $statement->execute($data);
        }
        catch (\Exception $e){
            die($e->getMessage(). " (update in Model.php on $table)");
        }
    }
    /**
     * @param $table - Table used to define where to update data
     * @param $data - Data to insert
     * @param $condition - Condition to set
     */
    public function delete($table, $data, $condition){
        /*
         * We could probably make the string a bit neater, i.e. loop through, create string with :$col etc. Didn't want to spend too much time on it.
         * WHERE col = :col, col2 = :col2
         * All data must be placed accordingly without : in the $data array.
         */
        try {
            $statement = $this->c->prepare("DELETE FROM $table WHERE $condition");
            $statement->execute($data);
        }
        catch (\Exception $e){
            die($e->getMessage(). " (delete in Model.php on $table)");
        }
    }

    /*
     * Start a database transaction
     */
    public function startTransaction(){
        $this->c->beginTransaction();
    }

    /*
    Commit a database transaction
    */
    public function commit(){
        $this->c->commit();
    }

    /*
     * Rollback a database transaction
     */
    public function rollBack(){
        $this->c->rollBack();
    }
}