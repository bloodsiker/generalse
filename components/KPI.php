<?php

namespace Umbrella\components;

/**
 * Calculate KPI the result service centers
 * Class KPI
 */
class KPI
{
    const ROUND_TO_THE_POINT = 1;
    const email_CSAT = 87;
    const call_CSAT = 90;
    const ECR = 40;
    const Order_TAT = 90;
    const Repair_TAT = 90;
    const SW_repair_TAT = 90;
    const SO_creation_TAT = 95;
    const L0_Rate = 30;
    const PPl = '1,15';
    const LongTail_14_days = 90;
    const LongTail_21_days = 5;
    const FTP_30_days = 4;
    const FTP_90_days = 6;
    const L2_Rate = 5;
    const Refund_Rate = 5;
    const LS_Rate = 40;


    /**
     * KPI constructor.
     * @param $name_partner
     * @param $start
     * @param $end
     */
    public function __construct($name_partner, $start, $end)
    {
        $this->name = $name_partner;
        $this->start = $start . ' 00:00';
        $this->end = $end . ' 23:59';

    }


    /**
     * КВР Количество всех ремонтов для определенного партнера
     * @return mixed
     */
    public function KBP()
    {
        return Data::getKBP($this->name, $this->start, $this->end);
    }


    /**
     * КРЧЗ Количество ремонтов с использованием запчасти, исключая значение SWAPLAB, PHREPL0, DPSTDW3, DPSTDW1
     * @return mixed
     */
    public function KRZCH()
    {
        return Data::getKRZCH($this->name, $this->start, $this->end);
    }


    /**
     * КРБЗ Количество ремонтов БЕЗ использованием запчасти, и наличие значений PHREPL0, DPSTDW3, DPSTDW1
     * @return mixed
     */
    public function KRBZ()
    {
        return Data::getKRBZ($this->name, $this->start, $this->end);
    }


    /**
     * 1) email_CSAT
     * @return float
     */
    public function email_CSAT(){

        // Получаем промежуток недель, в котором делать выборку
        $start_day = $this->start;
        $end_day = $this->end;

        // Номерация недели в году
        $start_week = Data::getCountMountForYear($start_day);
        $end_week = Data::getCountMountForYear($end_day);

        if(strlen($start_week) < 2){
            $start_week = 0 . $start_week;
        }
        if(strlen($end_week) < 2){
            $end_week = 0 . $end_week;
        }

        // Получаем дату в виде 2016-49 для выборки
        $crop_start_day = $start_day[0] . $start_day[1] . $start_day[2] . $start_day[3] . "-" . $start_week;
        $crop_end_day = $end_day[0] . $end_day[1] . $end_day[2] . $end_day[3] . "-" . $end_week;

        $data = Data::email_CSAT($this->name, $crop_start_day, $crop_end_day);

        // Удаляем пустые елементы массива
        $array_not_empty =  [];
        for($i = 0; $i < Count($data); $i++) {
            $array_not_empty[$i] = array_diff($data[$i], array(''));
        }

        // Считаем среднее значение и создаем новый массив
        $m = 0;
        $array_sum = [];
        foreach($array_not_empty as $item => $val){
            $sum = array_sum($val);
            $count = count($val);
            $array_sum[$m] = $sum / $count;
            $m++;
        }

        $new_sum = array_sum($array_sum);
        $new_count = count($array_sum);
        if($new_count == 0){
            return 0;
        }
        return round($new_sum / $new_count, 0);
    }


    /**
     * 2) call_CSAT
     * @return float
     */
    public function call_CSAT()
    {
        $data = Data::call_CSAT($this->name, $this->start, $this->end);

        $result = round($data * 10, 0);
        return $result;
    }


    /**
     *  3) ECR
     * @return mixed
     */
    public function ECR()
    {
        $data = Data::ECR($this->name, $this->start, $this->end);

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round(($data) / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }
        return str_replace('.',',', $result);
    }

    /**
     *  4) Order_TAT
     * @return float
     */
    public function Order_TAT()
    {
        $data = Data::Order_TAT($this->name, $this->start, $this->end);
        if(self::KRZCH() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KRZCH() / 100), 0);
        }
        return $result;
    }


    /**
     *  5) Repair_TAT
     * @return float
     */
    public function Repair_TAT()
    {
        $data = Data::Repair_TAT($this->name, $this->start, $this->end);

        if(self::KRZCH() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KRZCH() / 100), 0);
        }
        return $result;
    }


    /**
     *  6) SW_Repair_TAT
     * @return float
     */
    public function SW_Repair_TAT()
    {
        $data = Data::SW_Repair_TAT($this->name, $this->start, $this->end);

        if(self::KRBZ() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KRBZ() / 100), 0);
        }
        return $result;
    }


    /**
     *  7) SO_Creation_TAT
     * @return float
     */
    public function SO_Creation_TAT()
    {
        $data = Data::SO_Creation_TAT($this->name, $this->start, $this->end);

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KBP() / 100), 0);
        }

        return $result;
    }


    /**
     *  8) L0_Rate
     * @return float
     */
    public function L0_Rate()
    {
        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round((self::KRBZ()) / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }
        return str_replace('.',',', $result);
    }


    /**
     *  9) PPl
     * @return float
     */
    public function PPl()
    {
        $data = Data::PPl($this->name, $this->start, $this->end);

        if(self::KRZCH() == 0){
            $result =  0;
        } else {
            $result =  round($data / self::KRZCH(), 2);
        }

        return str_replace('.',',', $result);
        //return $result;
    }


    /**
     * 10) LongTail_14_Days
     * @return float
     */
    public function LongTail_14_Days()
    {
        if($this->name == 'GS Servisa'){
            $count_day = 31;
        } else {
            $count_day = 16;
        }
        $data = Data::LongTail_14_Days($this->name, $this->start, $this->end, $count_day);

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KBP() / 100), 0);
        }

        return $result;
    }

    /**
     * 11) LongTail_21_Days
     * @return float
     */
    public function LongTail_21_Days()
    {
        if($this->name == 'GS Servisa'){
            $count_day = 35;
        } else {
            $count_day = 21;
        }
        $data = Data::LongTail_21_Days($this->name, $this->start, $this->end, $count_day);

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }

        return str_replace('.',',', $result);
    }


    /**
     * 12) FTP_30_DAYS
     * @return float
     */
    public function FTP_30_DAYS()
    {
        $start = $this->start;
        $end = $this->end;
        $data = Data::FTF_30_Days($this->name, Functions::addDays($start, '-30 days'), $end);

        $so = "";
        $ser_n = "";
        $i = 0;
        $so_date = "";
        $ser_n_date = "";
        foreach($data as $al){
            //Если текущий и предыдущий Serial_Number равны - идем дальше.
            if($al['Serial_Number'] == $ser_n ){
                //Если текущий и предыдущий SO_NUMBER равны - ничего не делаем.
                if($al['SO_NUMBER'] == $so){

                } else {
                    //Если текущий и предыдущий SO_NUMBER равны - а вот здесь идем дальше.

                    // Сравниваем, какой номер SO_NUMBER больше, екущий или предыдущий - берем соответствующие даты для вычтения разницы.
                    if($al['SO_NUMBER'] > $so){
                        $date_start = $al['SO_CREATION_DATE'];
                        $date_end = $ser_n_date;
                    } else if($al['SO_NUMBER'] < $so) {
                        $date_start = $so_date;
                        $date_end = $al['Service_Complete_Date'];
                    }
                    $diff = (int)Functions::calcDateEnd($date_start, $date_end);

                    if( $diff > -30 && $diff < 0){

                        if($al['SO_NUMBER'] > $so){
                            $resul_unix = strtotime($al['Service_Complete_Date']);
                            $start_unix = strtotime($start);
                        } else if($al['SO_NUMBER'] < $so) {
                            $resul_unix = strtotime($ser_n_date);
                            $start_unix = strtotime($start);
                        }
                        if($resul_unix >= $start_unix){
                            $i++;
                        }
                    }
                }
            }
            $so = $al['SO_NUMBER'];
            $so_date = $al['SO_CREATION_DATE'];
            $ser_n = $al['Serial_Number'];
            $ser_n_date = $al['Service_Complete_Date'];
        }

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($i / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }
        return str_replace('.',',', $result);
    }


    /**
     * 13)FTP_90_DAYS
     * @return mixed
     */
    public function FTP_90_DAYS()
    {
        $start = $this->start;
        $end = $this->end;
        $data = Data::FTF_30_Days($this->name, Functions::addDays($start, '-90 days'), $end);

        $so = "";
        $ser_n = "";
        $i = 0;
        $so_date = "";
        $ser_n_date = "";
        foreach($data as $al){
            //Если текущий и предыдущий Serial_Number равны - идем дальше.
            if($al['Serial_Number'] == $ser_n ){
                //Если текущий и предыдущий SO_NUMBER равны - ничего не делаем.
                if($al['SO_NUMBER'] == $so){

                } else {
                    //Если текущий и предыдущий SO_NUMBER равны - а вот здесь идем дальше.

                    // Сравниваем, какой номер SO_NUMBER больше, екущий или предыдущий - берем соответствующие даты для вычтения разницы.
                    if($al['SO_NUMBER'] > $so){
                        $date_start = $al['SO_CREATION_DATE'];
                        $date_end = $ser_n_date;
                    } else if($al['SO_NUMBER'] < $so) {
                        $date_start = $so_date;
                        $date_end = $al['Service_Complete_Date'];
                    }
                    $diff = (int)Functions::calcDateEnd($date_start, $date_end);

                    if( $diff > -91 && $diff < 0){

                        if($al['SO_NUMBER'] > $so){
                            $resul_unix = strtotime($al['Service_Complete_Date']);
                            $start_unix = strtotime($start);
                        } else if($al['SO_NUMBER'] < $so) {
                            $resul_unix = strtotime($ser_n_date);
                            $start_unix = strtotime($start);
                        }
                        if($resul_unix >= $start_unix){
                            $i++;
                        }
                    }
                }
            }
            $so = $al['SO_NUMBER'];
            $so_date = $al['SO_CREATION_DATE'];
            $ser_n = $al['Serial_Number'];
            $ser_n_date = $al['Service_Complete_Date'];
        }

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($i / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }
        return str_replace('.',',', $result);
    }


    /**
     *  14) LS_Rate_N
     * @return float
     */
    public function L2_Rate()
    {
        $data = Data::Refund_Rate($this->name, 'Resoldering', $this->start, $this->end);

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round($data / (self::KBP() / 100), self::ROUND_TO_THE_POINT);
        }

        return str_replace('.',',', $result);
    }


    /**
     * 15) Refund_Rate
     * @return float
     */
    public function Refund_Rate()
    {
        $data_refund = Data::Refund_Rate($this->name, 'Return / reimbursement', $this->start, $this->end);

        $data = self::KBP() + $data_refund;

        if(self::KBP() == 0){
            $result =  0;
        } else {
            $result = round(($data_refund) / ($data / 100), self::ROUND_TO_THE_POINT);
        }

        return str_replace('.',',', $result);
    }


    /**
     * 16)
     * @return float
     */
    public function LS_Rate()
    {
        $data = Data::LS_Rate($this->name, $this->start, $this->end);

        if(self::KRZCH() == 0){
            $result =  0;
        } else {
            $result = round(($data) / (self::KRZCH() / 100), self::ROUND_TO_THE_POINT);
        }

        return str_replace('.',',', $result);
    }


    /**
     * Level of implementation of the norm
     * @param $result
     * @param $target
     */
    public function controlTargetUp($result, $target){

        if( $result > ($target)){
            echo "green";
        } elseif($result <= $target){
            if($result >= ($target - 1) && $result <= $target){
                echo "yellow";
            } else {
                echo "red problem";
            }
        }
    }


    /**
     * Level of implementation of the norm
     * @param $result
     * @param $target
     */
    public function controlTargetDown($result, $target){

        if( $result <= ($target)){
            echo "green";
        } elseif($result > $target){
            if($result < ($target + 1) && $result > $target){
                echo "yellow";
            } else {
                echo "red problem";
            }
        }

    }

    /**
     * Level of implementation of the norm
     * @param $result
     * @param $target
     */
    public function controlTargetPPl($result, $target){

        if( $result <= ($target)){
            echo "green";
        } elseif($result > $target){
            echo "red problem";
        }
    }


}