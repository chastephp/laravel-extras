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

if (!function_exists('array_sort_by_indexes')) {
    /**
     * 二维数组按照指定key指定顺序排序
     * @param  array  $array
     * @param  array  $indexes
     * @param  string  $key
     * @return array
     */
    function array_sort_by_indexes(array $array, array $indexes, string $key = 'id'): array
    {
        usort($array, function ($a, $b) use ($indexes, $key) {
            return (array_search($a[$key], $indexes) < array_search($b[$key], $indexes)) ? -1 : 1;
        });
        return $array;
    }
}
