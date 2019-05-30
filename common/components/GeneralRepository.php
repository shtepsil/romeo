<?php

namespace common\components;

use Yii;

use backend\controllers\MainController as d;
use yii\base\Exception;

class GeneralRepository
{
    /**
     * Creates and executes an INSERT SQL statement for several rows.
     *
     * Usage:
     * $rows = array(
     *      array('id' => 1, 'name' => 'John'),
     *      array('id' => 2, 'name' => 'Mark')
     * );
     * GeneralRepository::insertSeveral(User::model()->tableName(), $rows);
     *
     * @param string $table the table that new rows will be inserted into.
     * @param array $array_columns the array of column datas array(array(name=>value,...),...) to be inserted into the table.
     * @return integer number of rows affected by the execution.
     */
    public static function insertSeveral($table, $array_columns)
    {
        $connection = Yii::$app->db;
        $sql = '';
        $params = [];
        $i = 0;
        $data = [];
        $data['errors'] = false;

        foreach ($array_columns as $columns) {
            $names = array();
            $placeholders = array();
            foreach ($columns as $name => $value) {
                if (!$i) {
                    $names[] = $connection->quoteColumnName($name);
                }
                if ($value instanceof CDbExpression) {
                    $placeholders[] = $value->expression;
                    foreach ($value->params as $n => $v)
                        $params[$n] = $v;
                } else {
//                    $placeholders[] = ':' . $name . $i;
                    $placeholders[] = $value;
                    $params[':' . $name . $i] = $value;
                }
            }
            preg_match('/\'/', $placeholders[1], $matches);
            if($matches) $placeholders[1] = addslashes($placeholders[1]);
            if (!$i) {
                $sql = 'INSERT INTO ' . $connection->quoteTableName($table)
                    . ' (' . implode(', ', $names) . ') VALUES (\''
                    . implode('\', \'', $placeholders) . '\')';
            } else {
                $sql .= ',(\'' . implode('\', \'', $placeholders) . '\')';
            }
            $i++;
        }

//        d::td($sql);

        $command = $connection->createCommand($sql);

        try {
            $command->execute();
            d::td('Записано');
        }catch (Exception $e){
            d::td($e->getMessage());
            preg_match('/1062/',$e->getMessage(),$matches2);
            if($matches2) {
                $data['errors'] = d::getMessage('DUPLICATE_ENTRY');
//                d::td($e->getMessage());
            }
        }

        return $data;

    }
}