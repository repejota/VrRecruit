<?php
namespace Vreasy\Models;

use Vreasy\Query\Builder;

/**
 * Class Task
 * @package Vreasy\Models
 */
class Task extends Base
{
    // Different status codes for a task
    const STATUS_PENDING = "pending";
    const STATUS_ACCEPTED = "accepted";
    const STATUS_REFUSED = "refused";
    const STATUS_COMPLETED = "completed";

    // Protected attributes should match table columns
    protected $id;
    protected $deadline;
    protected $assigned_name;
    protected $assigned_phone;
    protected $created_at;
    protected $updated_at;
    protected $status;

    /**
     * Task constructor
     */
    public function __construct()
    {
        // Default field values
        $this->status = Task::STATUS_PENDING;
        $this->created_at = gmdate(DATE_FORMAT);
        $this->updated_at = $this->created_at;

        // Validation is done run by Valitron library
        $this->validates(
            'required',
            ['status', 'deadline', 'assigned_name', 'assigned_phone']
        );
        $this->validates(
            'date',
            ['created_at', 'updated_at']
        );
        $this->validates(
            'integer',
            ['id']
        );
    }

    /**
     * Save a task to database
     * @return mixed
     */
    public function save()
    {
        // Base class forward all static:: method calls directly to Zend_Db
        if ($this->isValid()) {
            $this->updated_at = gmdate(DATE_FORMAT);
            if ($this->isNew()) {
                $this->created_at = $this->updated_at;
                static::insert('tasks', $this->attributesForDb());
                $this->id = static::lastInsertId();
            } else {
                static::update('tasks', $this->attributesForDb(), ['id = ?' => $this->id]);
            }
            return $this->id;
        }
    }

    /**
     * Get a task by its Id
     * @param $id
     * @return mixed|Task
     */
    public static function findOrInit($id)
    {
        $task = new Task();
        if ($tasksFound = static::where(['id' => (int)$id])) {
            $task = array_pop($tasksFound);
        }
        return $task;
    }


    /**
     * Search tasks in the database
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
            ['wildcard' => true, 'prefix' => 't.']);

        // Select header
        $select = "SELECT t.* FROM tasks AS t";

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

    /**
     * Accepts the task and updates update_at
     * @return $this
     */
    public function accept($from)
    {
        // Change update_at timestamp field
        $this->updated_at = gmdate(DATE_FORMAT);
        $this->assigned_phone = $from;

        // Change the status field
        $this->status = Task::STATUS_ACCEPTED;

        return $this;
    }

    /**
     * Refuses the task and updates update_at
     * @return $this
     */
    public function refuse($from)
    {
        // Change update_at timestamp field
        $this->updated_at = gmdate(DATE_FORMAT);
        $this->assigned_phone = $from;

        // Change the status field
        $this->status = Task::STATUS_REFUSED;

        return $this;
    }

    /**
     * Completes the task and updates update_at
     * @return $this
     */
    public function complete($from)
    {
        // Change update_at timestamp field
        $this->updated_at = gmdate(DATE_FORMAT);
        $this->assigned_phone = $from;

        // Change the status field
        $this->status = Task::STATUS_COMPLETED;

        return $this;
    }
}
