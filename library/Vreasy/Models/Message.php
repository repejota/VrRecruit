<?php
namespace Vreasy\Models;

use Vreasy\Query\Builder;

class Message extends Base
{
    protected $id;
    protected $task;
    protected $status;
    protected $created_at;
    protected $from;
    protected $message;

    public function __construct()
    {
        // Validation is done run by Valitron library
        $this->validates(
            'required',
            ['task', 'status', 'created_at']
        );
        $this->validates(
            'date',
            ['created_at']
        );
        $this->validates(
            'integer',
            ['id', 'task']
        );
    }

    /**
     * Save a message task message to database
     * @return mixed
     */
    public function save()
    {
        if ($this->isValid()) {
            if ($this->isNew()) {
                static::insert('messages', $this->attributesForDb());
                $this->id = static::lastInsertId();
            } else {
                static::update('messages', $this->attributesForDb(), ['id = ?' => $this->id]);
            }
            return $this->id;
        }
    }

    /**
     * Search task messages in the database
     * @param $params
     * @param array $opts
     * @return array
     */
    public static function where($params, $opts = [])
    {
        // Default options' values
        $limit = 0;
        $start = 0;
        $orderBy = ['created_at'];
        $orderDirection = ['asc'];
        extract($opts, EXTR_IF_EXISTS);
        $orderBy = array_flatten([$orderBy]);
        $orderDirection = array_flatten([$orderDirection]);

        // Return value
        $collection = [];
        // Build the query
        list($where, $values) = Builder::expandWhere(
            $params,
            ['wildcard' => true, 'prefix' => 'm.']);

        // Select header
        $select = "SELECT m.* FROM messages AS m";

        // Build order by
        foreach ($orderBy as $i => $value) {
            $dir = isset($orderDirection[$i]) ? $orderDirection[$i] : 'ASC';
            $orderBy[$i] = "`$value` $dir";
        }
        $orderBy = implode(', ', $orderBy);

        $limitClause = '';
        if ($limit) {
            $limitClause = "LIMIT $start, $limit";
        }

        $orderByClause = '';
        if ($orderBy) {
            $orderByClause = "ORDER BY $orderBy";
        }
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "$select $where $orderByClause $limitClause";
        if ($res = static::fetchAll($sql, $values)) {
            foreach ($res as $row) {
                $collection[] = static::instanceWith($row);
            }
        }
        return $collection;
    }
}