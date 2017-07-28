<?php

namespace Umbrella\controllers\umbrella\crm;

use Umbrella\app\AdminBase;
use Umbrella\app\User;
use Umbrella\components\Logger;
use Umbrella\models\Admin;
use Umbrella\models\Disassembly;

/**
 * Class DisassemblyController
 */
class DisassemblyController extends AdminBase
{

    ##############################################################################
    ###########################     DISASSEMBLY      #############################
    ##############################################################################

    /**
     * DisassemblyController constructor.
     */
    public function __construct()
    {
        self::checkDenied('crm.disassembly', 'controller');
    }

    /**
     * Разборка устройств
     * @return bool
     */
    public function actionDisassembly()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $partnerList = Admin::getAllPartner();

        if (isset($_POST['search_serial'])) {
            $serial_number = $_POST['serial_number'];
            $id_partner = $_POST['id_partner'];

            //QB08242887
            $bomList = Disassembly::getRequestByPartner($id_partner, $serial_number);
        }
        require_once(ROOT . '/views/admin/crm/disassemble.php');
        return true;
    }

    /**
     * Постмотреть список заявок на разбор
     * @param string $filter
     * @return bool
     */
    public function actionDisassemblyResult($filter = '')
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner'){
            $filter = "";
            $interval = " AND gd.date_create >= DATE(NOW()) - INTERVAL 1 DAY";
            if(!empty($_GET['start'])){
                if(empty($_GET['end'])){
                    $end = date('Y-m-d') . " 23:59";
                } else {
                    $end = $_GET['end']. " 23:59";
                }
                $start = $_GET['start']. " 00:00";
                $filter .= " AND gd.date_create BETWEEN '$start' AND '$end'";
                $interval = "";
            }
            $filter .= $interval;
            $listDisassembly = Disassembly::getDisassemblyByPartner($user->controlUsers($userId), $filter);

            require_once(ROOT . '/views/admin/crm/disassemble_result_partner.php');

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager'){

            $filter = "";
            $interval = " AND gd.date_create >= DATE(NOW()) - INTERVAL 1 DAY";
            $partnerList = Admin::getAllPartner();

            if(!empty($_GET['start'])){
                if(empty($_GET['end'])){
                    $end = date('Y-m-d') . " 23:59";
                } else {
                    $end = $_GET['end']. " 23:59";
                }
                $start = $_GET['start']. " 00:00";
                $filter .= " AND gd.date_create BETWEEN '$start' AND '$end'";
                $interval = "";
            }

            if(!empty($_GET['id_partner'])){
                $id_partner = $_GET['id_partner'];
                $filter .= " AND gd.id_user = " .(int)$id_partner;
                $interval = "";
            }
            $filter .= $interval;
            $listDisassembly = Disassembly::getAllDisassembly($filter);

            require_once(ROOT . '/views/admin/crm/disassemble_result_admin.php');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function actionAllDisassembl()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $listDisassembly = Disassembly::getAllRequest();


        require_once(ROOT . '/views/admin/crm/all.php');
        return true;
    }

    /**
     * @return bool
     */
    public function actionDisassemblyAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        $data = $_REQUEST['json'];
        $data_json = json_decode($data, true);

        // Полачем последний номер разборки
        $lastId = Disassembly::getLastDecompileId();
        if($lastId == false){
            $lastId = 166;
        }
        $lastId++;

        $count = count($data_json);
        $i = 0;

        $options['site_id'] = $lastId;
        $options['part_number'] = $data_json[0]['dev_pn'];
        $options['serial_number'] = $data_json[0]['sn'];
        $options['note'] = $data_json[0]['note'];
        $options['dev_name'] = $data_json[0]['dev_name'];
        $options['stockName'] = $data_json[0]['stock_name'];
        $options['id_user'] = $data_json[0]['id_partner'];
        $options['ready'] = 1;
        // Разборка детали - шапка
        $okk = Disassembly::addDecompilesMsSql($options);
        //$okk = Disassembly::addDecompiles($options);
        if($okk){
            Disassembly::addDecompiles($options);

            // Перебор массива разборки и запись в бд
            foreach($data_json as $data){
                $options['site_id'] = $lastId;
                $options['mName'] = $data['desc'];
                $options['part_number'] = $data['pn'];
                //$options['serial_number'] = $data['sn'];
                $options['stock_name'] = $data['stock'];
                $options['quantity'] = $data['qua'];
                // Разборка детали
                $ok = Disassembly::addDecompilesPartsMsSql($options);
                //$ok = Disassembly::addDecompilesParts($options);
                if($ok){
                    Disassembly::addDecompilesParts($options);
                    $i++;
                }
            }
            Logger::getInstance()->log($user->id_user, 'произвел разборку устройства, SN ' . $options['serial_number']);
            // Кол-во обьектов в массиве должно быть равным кол-ву успешных записей в бд
            if($count == $i){
                echo 1;
            } else {
                echo 0;
            }
        }
        //print_r($data_json);
        return true;
    }

    /**
     * @return bool
     */
    public function actionDisassemblyActionAjax()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        // Обьект юзера
        $user = new User($userId);

        if($_REQUEST['action'] == 'accept'){
            $decompile_id = $_REQUEST['decompile_id'];
            $ok = Disassembly::updateStatusDisassemblyGM($decompile_id, 1, NULL);
            $status = [];
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'green';
                $status['text'] = 'Подтверждена';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'dismiss'){
            $decompile_id = $_REQUEST['decompile_id'];
            $comment = iconv('UTF-8', 'WINDOWS-1251', $_REQUEST['comment']);
            $ok = Disassembly::updateStatusDisassemblyGM($decompile_id, 2, $comment);
            $status = [];
            if($ok){
                $status['ok'] = 1;
                $status['class'] = 'red';
                $status['text'] = 'Отклонена';
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка подтверждения!';
            }
            echo json_encode($status);
        }

        if($_REQUEST['action'] == 'delete'){
            $site_id = $_REQUEST['site_id'];
            $ok = Disassembly::deleteDecompileById($site_id);
            $status = [];
            if($ok){
                $status['ok'] = 1;
            } else {
                $status['ok'] = 0;
                $status['error'] = 'Ошибка удаления!';
            }
            echo json_encode($status);
        }
        return true;
    }


    /**
     * Показать продукты с разборки
     * @return bool
     */
    public function actionShowDetailDisassembl()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        $site_id = $_REQUEST['site_id'];
        $data = Disassembly::getShowDetailsDisassembly($site_id);
        $comment = Disassembly::getShowCommentDisassembly($site_id);
        require_once (ROOT . '/views/admin/crm/disassemble_show_detailes.php');
        return true;
    }

    /**
     * Страница експорта
     * @param $data
     * @return bool
     */
    public function actionExportDisassembly($data)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();

        // Обьект юзера
        $user = new User($userId);

        if($user->role == 'partner'){

            $listExport =[];
            $start = '';
            $end = '';

            if(isset($_GET['start']) && !empty($_GET['start'])){
                $start = $_GET['start'] .' 00:00';
            }

            if(isset($_GET['end']) && !empty($_GET['end'])){
                $end = $_GET['end'] . ' 23:59';
            }

            $listExport = Disassembly::getExportDisassemblyByPartner($user->controlUsers($user->id_user), $start, $end);

        } else if($user->role == 'administrator' || $user->role == 'administrator-fin' || $user->role == 'manager' ){

            $listExport =[];
            $start = '';
            $end = '';

            if(isset($_GET['start']) && !empty($_GET['start'])){
                $start = $_GET['start'] .' 00:00';
            }

            if(isset($_GET['end']) && !empty($_GET['end'])){
                $end = $_GET['end'] . ' 23:59';
            }

            if(isset($_GET['id_partner']) && !empty($_GET['id_partner'])){
                if($_GET['id_partner'] == 'all'){
                    $listExport = Disassembly::getExportDisassemblyAllPartner($start, $end);
                } else {
                    $user_id = $_GET['id_partner'];
                    $listExport = Disassembly::getExportDisassemblyByPartner($user->controlUsers($user_id), $start, $end);
                }
            }
        }

        require_once (ROOT . '/views/admin/crm/export/disassemble.php');
        return true;
    }


    public function actionTest1()
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем идентификатор пользователя из сессии
        $userId = Admin::CheckLogged();
        $s1 = [305, 306, 307, 308, 309, 310, 311, 312, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350];
        $s2 = [351, 352, 353, 354, 355, 356, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 393, 404, 405, 406, 407, 408, 409];
        $s3 = [410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 427, 428, 429, 430, 431, 433, 434, 435, 436, 437, 438, 439, 440, 441, 442, 443, 444, 445, 446, 447, 448, 449, 450, 452, 472, 473, 474, 475, 476, 477, 478];
        $s4 = [479, 480, 481, 482, 483, 484, 485, 487, 488, 489, 490, 491, 493, 494, 495, 496, 497, 498, 499, 500, 501, 502, 503, 504, 505, 506, 507, 508, 509, 510, 511, 512, 513, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 524, 525, 526, 527, 528, 529, 530, 531];
        $s5 = [532, 533, 534, 535, 536, 536, 537, 538, 539, 540, 541, 542, 543, 544, 545, 546, 547, 548, 549, 550, 551, 552, 553, 554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566, 567, 568, 569, 570, 571, 572, 573, 574, 575, 576, 577, 578, 579, 580, 581, 582, 583, 584, 585, 586, 587, 588, 589, 590, 591];
        $s6 = [592, 593, 594, 595, 596, 597, 598, 599, 600, 601, 602, 603, 604, 605, 606, 607, 608, 609, 610, 611, 612, 613, 617, 619, 620, 621, 623, 624, 629, 630, 639, 640, 641, 642, 643, 644, 645, 646, 647, 648, 650, 651, 652, 653, 654, 655, 656, 657, 658, 659, 660];
        $s7 = [661, 662, 663, 664, 665, 666, 667, 668, 669, 670, 671, 672, 673, 674, 675, 676, 677, 678, 679, 680, 681, 682, 683, 684, 685, 686, 687, 688, 689, 690, 691, 692, 693, 694, 695, 696, 697, 698, 699, 700, 701, 702, 703, 704, 705, 706, 707, 708, 709, 710, 711, 712, 713, 714, 715, 716, 717, 718, 719, 720];
        $s8 = [721, 722, 723, 724, 725, 727, 728, 729, 730, 731, 732, 733, 734, 735, 736, 737, 738, 739, 740, 741, 742, 743, 744, 745, 746, 747, 748, 749, 750, 751, 752, 753, 754, 755, 756, 757, 758, 759, 760, 761, 762, 763, 764, 765, 766, 767, 768, 769, 770, 771, 772, 773, 774, 775, 776, 777, 778, 779, 780];
        $s9 = [781, 782, 783, 784, 785, 786, 787, 788, 788, 791, 791, 791, 792, 793, 794, 795, 796, 797, 798, 798, 799, 800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 810, 811, 812, 813, 814, 815, 816, 817, 818, 819, 820, 821, 822, 824, 825, 826, 829, 830, 831, 832, 833, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 847, 848, 849, 850];
        $s10 = [851, 852, 853, 854, 855, 856, 857, 858, 859, 860, 861, 862, 863, 864, 901, 902, 903, 904, 905, 906, 907, 909, 910, 911, 912, 913, 914, 915, 916, 917, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 934, 935, 936, 937, 938, 939, 940];

        $array_ms = [];
        $i = 0;
        foreach ($s1 as $key => $value){
            //echo $value . "<br>";
            $arr = Disassembly::getCountTestMysql($value);
            $array_ms[$i]['count'] = $arr[0]['count'];
            $i++;
        }

        //$status = iconv('UTF-8', 'WINDOWS-1251', 'Подтверждена');
        //$ms_decompile = Disassembly::getTestMysql($status);
        //$array_id = array_column($ms_decompile, 'site_id');
        //$string = implode(', ', $array_id);
//        $arr = Disassembly::getCountTestMysql(305);
//
//        echo "<pre>";
//        print_r($array_ms);


        $html = '<table>';
        foreach ($array_ms as $ms){
            $html .= '<tr>';
            $html .= "<td>{$ms['count']}</td>";
            $html .= '</tr>';
        }
        $html .= '</table>';
        echo $html;
        return true;
    }

}