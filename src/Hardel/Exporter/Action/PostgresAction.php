<?php
/**
 * @author: hernan ariel de luca
 * Date: 12/07/2017
 * Time: 15:17
 */

namespace Hardel\Exporter\Action;

use DB;
use Hardel\Exporter\AbstractAction;

class PostgresAction extends AbstractAction
{

    /**
     * @var array
     */
    protected $selects = [
        'column_name as Field',
        'udt_name as Type',
        'is_nullable as Null',
        'dtd_identifier as Key',
        'column_default as Default',
        'udt_name as Data_Type',
    ];

    public function write()
    {
        // TODO: Implement write() method.
    }

    public function convert($database = null)
    {
        return parent::convert($database); // TODO: Change the autogenerated stub
    }

    protected function compile()
    {
        // TODO: Implement compile() method.
    }

    /**
     * Get all the tables
     * @return mixed
     */
    protected function getTables()
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_catalog='" . $this->database . "' AND table_schema='public'");
    }

    public function getTableIndexes($table)
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query("SHOW INDEX FROM " . $table . " WHERE Key_name != 'PRIMARY'");
    }

    /**
     * Get all the columns for a given table
     * @param $table
     * @return mixed
     */
    protected function getTableDescribes($table)
    {
        return DB::table('information_schema.columns')
            ->where('table_catalog', '=', $this->database)
            ->where('table_name', '=', $table)
            ->where('table_schema', '=', 'public')
            ->get($this->selects);
    }

    /**
     * Grab all the table data
     * @param $table
     * @return mixed
     */
    protected function getTableData($table)
    {
        return DB::table($table)->get();
    }
}