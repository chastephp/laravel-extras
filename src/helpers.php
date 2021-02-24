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
     * @return \Illuminate\Database\Eloquent\Model|Eloquent
     */
    function model(string $table)
    {
        return new class($table) extends Illuminate\Database\Eloquent\Model {
            public function __construct($table)
            {
                $this->setTable($table);
                parent::__construct();
            }
        };
    }
}
