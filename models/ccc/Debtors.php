<?php
namespace Umbrella\models\ccc;

use PDO;
use Umbrella\components\Db\MySQL;

class Debtors
{

    public static function addData($options)
    {
        $db = MySQL::getConnection();

        $sql = 'INSERT INTO gs_ccc_debtors '
            . '(user_id, name_partner, order_number, sum_order, snipped_on, bill_number, bill_summa, bill_date, bill_status, 
            payment_to_date, payment_sum, payment_date, deferment, deferment_day, phones) '
            . 'VALUES '
            . '(:user_id, :name_partner, :order_number, :sum_order, :snipped_on, :bill_number, :bill_summa, :bill_date, :bill_status, 
            :payment_to_date, :payment_sum, :payment_date, :deferment, :deferment_day, :phones)';

        $result = $db->prepare($sql);
        foreach ($options as $value){
            $result->execute([
                'user_id' => $value['user_id'],
                'name_partner' => $value['name_partner'],
                'order_number' => $value['order_number'],
                'sum_order' => $value['sum_order'],
                'snipped_on' => $value['snipped_on'],
                'bill_number' => $value['bill_number'],
                'bill_summa' => $value['bill_summa'],
                'bill_date' => $value['bill_date'],
                'bill_status' => $value['bill_status'],
                'payment_to_date' => $value['payment_to_date'],
                'payment_sum' => $value['payment_sum'],
                'payment_date' => $value['payment_date'],
                'deferment' => $value['deferment'],
                'deferment_day' => $value['deferment_day'],
                'phones' => $value['phones'],
            ]);
        }
    }


    /**
     * @param string $filter
     *
     * @return mixed
     */
    public static function getAll($filter = '')
    {
        $db = MySQL::getConnection();

        $sql = "SELECT * FROM gs_ccc_debtors WHERE 1 = 1 {$filter} ORDER BY name_partner";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public static function getAllPartners()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                name_partner 
                FROM gs_ccc_debtors 
                GROUP BY name_partner 
                ORDER BY name_partner";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public static function getDeferment()
    {
        $db = MySQL::getConnection();

        $sql = "SELECT
                deferment 
                FROM gs_ccc_debtors 
                GROUP BY deferment";

        $result = $db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}