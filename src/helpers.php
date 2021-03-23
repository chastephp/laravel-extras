<?php
/**
 * User: widdy
 * Date: 2018/12/29
 * Time: 14:11
 */
if (!function_exists('model')) {
    /**
     * return a new Eloquent model with given table name.
     *
     * @param $table
     * @param $connection
     * @return \Illuminate\Database\Eloquent\Model|Eloquent
     */
    function model(string $table, string $connection = '')
    {
        return new class($table, $connection) extends Illuminate\Database\Eloquent\Model {
            public function __construct($table, $connection)
            {
                $this->setConnection($connection);
                $this->setTable($table);
                parent::__construct();
            }
        };
    }
}
