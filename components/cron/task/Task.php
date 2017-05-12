<?php
require_once dirname(__FILE__) . "/TaskListFunction.php";

class Task
{

    public function __construct()
    {

    }

    /**
     * Список текущих заданий
     * @return array
     */
    public function listTask()
    {
        return TaskListFunction::getTaskList();
    }

    public function taskFactory()
    {
        $list_task = self::listTask();
        foreach ($list_task as $task){
            // Список пользователей, которых применяем в выборке
            $array_user = json_decode($task['step_1'], true);
            $list_user = self::fromArrayToList($array_user);

            // Список складов, которые применяем к выборке
            $array_stock = json_decode($task['step_4'], true);
            $list_stock = self::fromArrayToList($array_stock, '\' , \'');
            $section = $task['step_2'];

            // Массив строк за которые уже начисленно на баланс
            $array_id_completed = TaskListFunction::getCompletedRowSection($section, $task['id']);
            $list_id_completed = self::fromCompletedRowArrayToList($array_id_completed);
            $status = iconv('UTF-8', 'WINDOWS-1251', $task['step_3']);

            // Диапазон цен для начиления
            $labor_cast = json_decode($task['step_7'], true);

            if($section == 'Purchase'){
                $filter = '';
                if(!empty($list_stock)){
                    $filter .= " AND sgp.stock_name IN('{$list_stock}')";
                }
                if(!empty($list_id_completed)){
                    $filter .= " AND sgp.purchase_id NOT IN({$list_id_completed})";
                }
                $max_id = TaskListFunction::getMaxIdIsCompleted($section, $task['id']);
                // Получаем массив покупок с которым будем работать
                $list_purchase = self::getList($section, $list_user, $status, $filter, $max_id);

                foreach ($list_purchase as $purchase){
                    $add_price = (float)0;
                    // Получаем элементы покупок, проходимся по деталям
                    $list_purchase_elements = TaskListFunction::getPurchasesElementList($purchase['purchase_id']);
                    $completed = [];
                    $i = 0;
                    foreach ($list_purchase_elements as $element){
                        $price = (float)0;
                        $classifier = iconv("WINDOWS-1251", "UTF-8", $element['classifier']);
                        $goods_sub_type = iconv("WINDOWS-1251", "UTF-8", $element['goods_sub_type']);
                        // Получаем цены деталей и сравниваем с диапазоном цен Labor cast и добавляем соответствующую сумму к детали
                        foreach ($labor_cast as $cast){
                            if($element['price'] >= $cast['min'] && $element['price'] <= $cast['max']){
                                $price = ((float)$element['price'] + (float)$cast['add']) * (int)$element['quantity'];
                            }
                        }
                        $add_price += $price;

                        $completed[$i]['section'] = $section;
                        $completed[$i]['id_row'] = $purchase['purchase_id'];
                        $completed[$i]['id_task_list'] = $task['id'];
                        $completed[$i]['part_number'] = $element['part_number'];
                        $completed[$i]['goods_name'] = iconv("WINDOWS-1251", "UTF-8",$element['goods_name']);
                        $completed[$i]['quantity'] = $element['quantity'];
                        $completed[$i]['stock_name'] = $element['stock_name'];
                        $completed[$i]['classifier'] = $classifier;
                        $completed[$i]['goods_sub_type'] = $goods_sub_type;
                        $completed[$i]['price'] = $element['price'];
                        $completed[$i]['add_pay'] = $price;
                        $i++;
                    }
                    $id_number = null;

                    // Кому начисляем выплату, если 0 - то списку пользователей из $task['step_1'], иначе id пользователя
                    if($task['whom_payment'] == 0){
                        $options['id_user'] = $purchase['site_account_id'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($purchase['site_account_id']);
                    } else {
                        $options['id_user'] = $task['whom_payment'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($task['whom_payment']);
                    }
                    $options['balance'] = $add_price;
                    $options['id_number'] = $id_number['id'];
                    $options['action_balance'] = 'Зачисление';
                    $options['id_task'] = $task['id'];
                    $options['id_customer'] = $task['step_8'];
                    $options['section'] = $section;
                    $options['id_row_section'] = $purchase['purchase_id'];
                    $options['date_create'] = date('Y-m-d H:i:s');
                    $ok = TaskListFunction::accrualBalance($options);
                    if($ok){
                        TaskListFunction::addCompletedRowSection($section, $purchase['purchase_id'], $task['id']);
                        // Пишем в лог, за какие элементы было произведенно начисление
                        foreach ($completed as $value){
                            TaskListFunction::addCompletedElements($value);
                        }
                    }
                }

            } elseif($section == 'Order'){

                $filter = '';
                if(!empty($list_stock)){
                    $filter .= " AND sgoe.stock_name IN('{$list_stock}')";
                }
                if(!empty($list_id_completed)){
                    $filter .= " AND sgo.order_id NOT IN({$list_id_completed})";
                }
                $max_id = TaskListFunction::getMaxIdIsCompleted($section, $task['id']);
                // Получаем массив заказов с которым будем работать
                $list_orders_elements = self::getList($section, $list_user, $status, $filter, $max_id);
                $completed = [];
                //$add_price = (float)0;
                foreach ($list_orders_elements as $element){
                    $price = (float)0;
                    $classifier = iconv("WINDOWS-1251", "UTF-8", $element['classifier']);
                    $goods_sub_type = iconv("WINDOWS-1251", "UTF-8", $element['goods_sub_type']);
                    // Получаем цены деталей и сравниваем с диапазоном цен Labor cast и добавляем соответствующую сумму к детали
                    foreach ($labor_cast as $cast){
                        if($goods_sub_type == $cast['goods_sub_type'] && $classifier == $cast['classifier']){
                            $price += (float)$cast['add'] * (int)$element['quantity'];
                        }
                    }
                    //$add_price += $price;

                    $completed['section'] = $section;
                    $completed['id_row'] = $element['order_id'];
                    $completed['id_task_list'] = $task['id'];
                    $completed['part_number'] = $element['part_number'];
                    $completed['goods_name'] = iconv("WINDOWS-1251", "UTF-8", $element['goods_name']);
                    $completed['quantity'] = $element['quantity'];
                    $completed['stock_name'] = $element['stock_name'];
                    $completed['classifier'] = $classifier;
                    $completed['goods_sub_type'] = $goods_sub_type;
                    $completed['add_pay'] = $price;

                    $id_number = null;

                    // Кому начисляем выплату, если 0 - то списку пользователей из $task['step_1'], иначе id пользователя
                    if($task['whom_payment'] == 0){
                        $options['id_user'] = $element['site_account_id'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($element['site_account_id']);
                    } else {
                        $options['id_user'] = $task['whom_payment'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($task['whom_payment']);
                    }
                    $options['balance'] = $price;
                    $options['id_number'] = $id_number['id'];
                    $options['action_balance'] = 'Зачисление';
                    $options['id_task'] = $task['id'];
                    $options['id_customer'] = $task['step_8'];
                    $options['section'] = $section;
                    $options['id_row_section'] = $element['order_id'];
                    $options['date_create'] = date('Y-m-d H:i:s');
                    $ok = TaskListFunction::accrualBalance($options);
                    if($ok){
                        TaskListFunction::addCompletedRowSection($section, $element['order_id'], $task['id']);
                        // Пишем в лог, за какие элементы было произведенно начисление
                        TaskListFunction::addCompletedElements($completed);
                    }
                }

//                echo '<pre>';
//                print_r($add_price);
//                echo '</pre>';

            } elseif($section == 'Return'){

                $filter = '';
                if(!empty($list_id_completed)){
                    $filter .= " AND sgs.stock_return_id NOT IN({$list_id_completed})";
                }
                $max_id = TaskListFunction::getMaxIdIsCompleted($section, $task['id']);
                // Получаем массив разборок с которым будем работать
                $list_return_elements = self::getList($section, $list_user, $status, $filter, $max_id);
                $completed = [];
                //$add_price = (float)0;
                foreach ($list_return_elements as $element){
                    $price = (float)0;
                    $classifier = iconv("WINDOWS-1251", "UTF-8", $element['classifier']);
                    $goods_sub_type = iconv("WINDOWS-1251", "UTF-8", $element['goods_sub_type']);
                    // Получаем цены деталей и сравниваем с диапазоном цен Labor cast и добавляем соответствующую сумму к детали
                    foreach ($labor_cast as $cast){
                        if($element['site_account_id'] == $cast['id_user'] && $classifier == $cast['classifier']){
                            $price += (float)$cast['add'] * (int)$element['quantity'];
                        }
                    }
                    //$add_price += $price;

                    $completed['section'] = $section;
                    $completed['id_row'] = $element['stock_return_id'];
                    $completed['id_task_list'] = $task['id'];
                    $completed['part_number'] = $element['part_number'];
                    $completed['goods_name'] = iconv("WINDOWS-1251", "UTF-8", $element['goods_name']);
                    $completed['quantity'] = $element['quantity'];
                    $completed['stock_name'] = $element['stock_name'];
                    $completed['classifier'] = $classifier;
                    $completed['goods_sub_type'] = $goods_sub_type;
                    $completed['add_pay'] = $price;

                    $id_number = null;

                    // Кому начисляем выплату, если 0 - то списку пользователей из $task['step_1'], иначе id пользователя
                    if($task['whom_payment'] == 0){
                        $options['id_user'] = $element['site_account_id'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($element['site_account_id']);
                    } else {
                        $options['id_user'] = $task['whom_payment'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($task['whom_payment']);
                    }
                    $options['balance'] = $price;
                    $options['id_number'] = $id_number['id'];
                    $options['action_balance'] = 'Зачисление';
                    $options['id_task'] = $task['id'];
                    $options['id_customer'] = $task['step_8'];
                    $options['section'] = $section;
                    $options['id_row_section'] = $element['stock_return_id'];
                    $options['date_create'] = date('Y-m-d H:i:s');
                    $ok = TaskListFunction::accrualBalance($options);
                    if($ok){
                        TaskListFunction::addCompletedRowSection($section, $element['stock_return_id'], $task['id']);
                        // Пишем в лог, за какие элементы было произведенно начисление
                        TaskListFunction::addCompletedElements($completed);
                    }
                }

            } elseif($section = 'Disassembly'){
                $filter = '';
                if(!empty($list_id_completed)){
                    $filter .= " AND sgd.decompile_id NOT IN({$list_id_completed})";
                }
                $max_id = TaskListFunction::getMaxIdIsCompleted($section, $task['id']);
                // Получаем массив разборок с которым будем работать
                $list_disassembly = self::getList($section, $list_user, $status, $filter, $max_id);

                foreach ($list_disassembly as $disassembly){
                    $add_price = (float)0;
                    $list_disassembly_elements = TaskListFunction::getDisassemblyElementList($disassembly['decompile_id']);
                    // массив с емелментами, за которые происведено начисление
                    $completed = [];
                    $i = 0;
                    foreach ($list_disassembly_elements as $element){
                        $price = (float)0;
                        $classifier = iconv("WINDOWS-1251", "UTF-8", $element['classifier']);
                        $goods_sub_type = iconv("WINDOWS-1251", "UTF-8", $element['goods_sub_type']);
                        // Получаем цены деталей и сравниваем с диапазоном цен Labor cast и добавляем соответствующую сумму к детали
                        foreach ($labor_cast as $cast){
                            if($classifier == $cast['classifier']){
                                $price += (float)$cast['add'] * (int)$element['quantity'];
                            }
                        }
                        $add_price += $price;

                        $completed[$i]['section'] = $section;
                        $completed[$i]['id_row'] = $disassembly['decompile_id'];
                        $completed[$i]['id_task_list'] = $task['id'];
                        $completed[$i]['part_number'] = $element['part_number'];
                        $completed[$i]['goods_name'] = iconv("WINDOWS-1251", "UTF-8", $element['goods_name']);
                        $completed[$i]['quantity'] = $element['quantity'];
                        $completed[$i]['stock_name'] = $element['stock_name'];
                        $completed[$i]['classifier'] = $classifier;
                        $completed[$i]['goods_sub_type'] = $goods_sub_type;
                        $completed[$i]['add_pay'] = $price;
                        $i++;
                    }

                    $id_number = null;

                    // Кому начисляем выплату, если 0 - то списку пользователей из $task['step_1'], иначе id пользователя
                    if($task['whom_payment'] == 0){
                        $options['id_user'] = $disassembly['site_account_id'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($disassembly['site_account_id']);
                    } else {
                        $options['id_user'] = $task['whom_payment'];
                        $id_number = TaskListFunction::getNumberBalanceByUser($task['whom_payment']);
                    }
                    $options['balance'] = $add_price;
                    $options['id_number'] = $id_number['id'];
                    $options['action_balance'] = 'Зачисление';
                    $options['id_task'] = $task['id'];
                    $options['id_customer'] = $task['step_8'];
                    $options['section'] = $section;
                    $options['id_row_section'] = $disassembly['decompile_id'];
                    $options['date_create'] = date('Y-m-d H:i:s');
                    $ok = TaskListFunction::accrualBalance($options);
                    if($ok){
                        TaskListFunction::addCompletedRowSection($section, $disassembly['decompile_id'], $task['id']);
                        // Пишем в лог, за какие элементы было произведенно начисление
                        foreach ($completed as $value){
                            TaskListFunction::addCompletedElements($value);
                        }
                    }
                }

            }
        }
    }


    /**
     * Получаем список по определенным раздем
     * @param $section
     * @param $list_user
     * @param $status
     * @param $filter
     * @param $max_id
     * @return array
     */
    public function getList($section, $list_user, $status, $filter, $max_id)
    {
        switch ($section){
            case 'Purchase':
                return  TaskListFunction::getPurchasesList($list_user, $status, $filter, $max_id - 100);
                break;
            case 'Order':
                return  TaskListFunction::getOrdersList($list_user, $status, $filter, $max_id - 100);
                break;
            case 'Return':
                return  TaskListFunction::getReturnList($list_user, $status, $filter, $max_id - 100);
                break;
            case 'Disassembly':
                return  TaskListFunction::getDisassemblyList($list_user, $status, $filter, $max_id - 100);
                break;
        }
    }


    /**
     * Возвращаем строку через розделитель
     * @param null $array
     * @param string $delimiter
     * @return bool|string
     */
    public function fromArrayToList($array = null, $delimiter = ',')
    {
        if(!is_array($array)){
            return null;
        }
        return implode($delimiter, $array);
    }

    public function fromCompletedRowArrayToList($array = null, $delimiter = ',')
    {
        if(!is_array($array)){
            return null;
        }
        // Альтернатива функции array_column($array, 'id_row')
        $array_column = array_map(function($element) {
            return $element['id_row'];
        }, $array);

        $string = implode($delimiter, $array_column);
        return $string;
    }


}