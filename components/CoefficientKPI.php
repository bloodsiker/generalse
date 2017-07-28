<?php

namespace Umbrella\components;

/**
 * рассчитываем коэффициент на основе показателей KPI
 * Class CoefficientKPI
 */
class CoefficientKPI
{
    const ROUND_TO_THE_POINT = 2;  // Кол-во знаков для округления, после точки
    const email_CSAT = 0.1;
    const call_CSAT = 0.1;
    const ECR = 0.05;
    const Order_TAT = 0.05;
    const Repair_TAT = 0.05;
    const SW_repair_TAT = 0.1;
    const SO_creation_TAT = 0.05;
    const L0_Rate = 0.1;
    const PPl = 0.1;
    const LongTail_14_days = 0.05;
    const LongTail_21_days = 0.05;
    const FTP_30_days = 0.05;
    const FTP_90_days = 0.05;
    const L2_Rate = 0.05;
    const Refund_Rate = 0.05;

    /**
     * CoefficientKPI constructor.
     * @param KPI $kpi
     */
    public function __construct(KPI $kpi)
    {
        $this->kpi = $kpi;
    }

    public function partnerName()
    {
        return $this->kpi->name;
    }

    /**
     * @return float
     */
    public function coefficientResult()
    {
//        $user = new User($_SESSION['user']);
//        $end_time = '23:59:59';
//        $now_time = date('H:i:s');

        $result = 0;
        $result += self::email_CSAT * $this->coefficientEmailCSAT();
        $result += self::call_CSAT * $this->coefficientCall_CSAT();
        $result += self::ECR * $this->coefficientECR();
        $result += self::Order_TAT * $this->coefficientOrder_TAT();
        $result += self::Repair_TAT * $this->coefficientRepair_TAT();
        $result += self::SW_repair_TAT * $this->coefficientSW_Repair_TAT();
        $result += self::SO_creation_TAT * $this->coefficientSO_Creation_TAT();
        $result += self::L0_Rate * $this->coefficientL0_Rate();
        $result += self::PPl * $this->coefficientPPl();
        $result += self::LongTail_14_days * $this->coefficientLongTail_14_Days();
        $result += self::LongTail_21_days * $this->coefficientLongTail_21_Days();
        $result += self::FTP_30_days * $this->coefficientFTP_30_DAYS();
        $result += self::FTP_90_days * $this->coefficientFTP_90_DAYS();
        $result += self::L2_Rate * $this->coefficientL2_Rate();
        $result += self::Refund_Rate * $this->coefficientRefund_Rate();
        $res = round($result, self::ROUND_TO_THE_POINT);
        //Admin::updateUserCoefficient($user->id_user, $res);

        return $res;
    }

    /**
     * @return float|int
     */
    public function coefficientEmailCSAT()
    {
        $rate = 0;
        if(self::partnerName() == 'GS Accent' || self::partnerName() == 'GS PitExim'){
            $rate = 1;
        } else {
            $result = $this->kpi->email_CSAT();
            if($result < 75){
                $rate = 0.85;
            } elseif($result >= 75 && $result <= 80){
                $rate = 0.9;
            } elseif($result >= 81 && $result <= 86){
                $rate = 0.95;
            } elseif($result >= 87 && $result <= 90){
                $rate = 1;
            } elseif($result >= 91){
                $rate = 1.05;
            }
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientCall_CSAT()
    {
        $result = $this->kpi->call_CSAT();
        $rate = 0;
        if($result < 80){
            $rate = 0.85;
        } elseif($result >= 80 && $result <= 85){
            $rate = 0.9;
        } elseif($result >= 86 && $result <= 89){
            $rate = 0.95;
        } elseif($result >= 90 && $result <= 94){
            $rate = 1;
        } elseif($result >= 95){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientECR()
    {
        $result = $this->kpi->ECR();
        $rate = 0;
        if($result < 20){
            $rate = 0.85;
        } elseif($result >= 20 && $result <= 29){
            $rate = 0.9;
        } elseif($result >= 30 && $result <= 39){
            $rate = 0.95;
        } elseif($result >= 40 && $result <= 45){
            $rate = 1;
        } elseif($result >= 45){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientOrder_TAT()
    {
        $result = $this->kpi->Order_TAT();
        $rate = 0;
        if($result < 80){
            $rate = 0.85;
        } elseif($result >= 80 && $result <= 85){
            $rate = 0.9;
        } elseif($result >= 86 && $result <= 89){
            $rate = 0.95;
        } elseif($result >= 90 && $result <= 94){
            $rate = 1;
        } elseif($result >= 95){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientRepair_TAT()
    {
        $result = $this->kpi->Repair_TAT();
        $rate = 0;
        if($result < 80){
            $rate = 0.85;
        } elseif($result >= 80 && $result <= 85){
            $rate = 0.9;
        } elseif($result >= 86 && $result <= 89){
            $rate = 0.95;
        } elseif($result >= 90 && $result <= 94){
            $rate = 1;
        } elseif($result >= 95){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientSW_Repair_TAT()
    {
        $result = $this->kpi->SW_Repair_TAT();
        $rate = 0;
        if($result < 80){
            $rate = 0.85;
        } elseif($result >= 80 && $result <= 85){
            $rate = 0.9;
        } elseif($result >= 86 && $result <= 89){
            $rate = 0.95;
        } elseif($result >= 90 && $result <= 94){
            $rate = 1;
        } elseif($result >= 95){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientSO_Creation_TAT()
    {
        $result = $this->kpi->SO_Creation_TAT();
        $rate = 0;
        if($result < 85){
            $rate = 0.85;
        } elseif($result >= 85 && $result <= 89){
            $rate = 0.9;
        } elseif($result >= 90 && $result <= 94){
            $rate = 0.95;
        } elseif($result >= 95 && $result <= 97){
            $rate = 1;
        } elseif($result >= 98){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientL0_Rate()
    {
        $result = $this->kpi->L0_Rate();
        $rate = 0;
        if($result < 10){
            $rate = 0.85;
        } elseif($result >= 10 && $result <= 19){
            $rate = 0.9;
        } elseif($result >= 20 && $result <= 29){
            $rate = 0.95;
        } elseif($result >= 30 && $result <= 39){
            $rate = 1;
        } elseif($result >= 40){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientPPl()
    {
        $result = $this->kpi->PPl();
        $rate = 0;
        if($result > '1,21'){
            $rate = 0.85;
        } elseif($result <= '1,21' && $result >= '1,19'){
            $rate = 0.9;
        } elseif($result <= '1,18' && $result >= '1,16'){
            $rate = 0.95;
        } elseif($result <= '1,15' && $result >= '1,11'){
            $rate = 1;
        } elseif($result <= '1,10'){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientLongTail_14_Days()
    {
        $result = $this->kpi->LongTail_14_Days();
        $rate = 0;
        if($result < 80){
            $rate = 0.85;
        } elseif($result >= 80 && $result <= 85){
            $rate = 0.9;
        } elseif($result >= 86 && $result <= 89){
            $rate = 0.95;
        } elseif($result >= 90 && $result <= 94){
            $rate = 1;
        } elseif($result >= 95){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientLongTail_21_Days()
    {
        $result = $this->kpi->LongTail_21_Days();
        $rate = 0;
        if($result > 9){
            $rate = 0.85;
        } elseif($result <= 9 && $result >= 8){
            $rate = 0.9;
        } elseif($result <= 7 && $result >= 6){
            $rate = 0.95;
        } elseif($result <= 5 && $result >= 4){
            $rate = 1;
        } elseif($result <= 3){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientFTP_30_DAYS()
    {
        $result = $this->kpi->FTP_30_DAYS();
        $rate = 0;
        if($result > 8){
            $rate = 0.85;
        } elseif($result <= 8 && $result >= 7){
            $rate = 0.9;
        } elseif($result <= 6 && $result >= 5){
            $rate = 0.95;
        } elseif($result <= 4 && $result >= 3){
            $rate = 1;
        } elseif($result <= 2){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientFTP_90_DAYS()
    {
        $result = $this->kpi->FTP_90_DAYS();
        $rate = 0;
        if($result > 10){
            $rate = 0.85;
        } elseif($result <= 10 && $result >= 9){
            $rate = 0.9;
        } elseif($result <= 8 && $result >= 7){
            $rate = 0.95;
        } elseif($result <= 6 && $result >= 5){
            $rate = 1;
        } elseif($result <= 4){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientL2_Rate()
    {
        $result = $this->kpi->L2_Rate();
        $rate = 0;
        if($result > 2){
            $rate = 0.85;
        } elseif($result >= 2 && $result <= 3){
            $rate = 0.9;
        } elseif($result >= 3 && $result <= 5){
            $rate = 0.95;
        } elseif($result >= 5 && $result <= 10){
            $rate = 1;
        } elseif($result >= 10){
            $rate = 1.05;
        }
        return $rate;
    }

    /**
     * @return float|int
     */
    public function coefficientRefund_Rate()
    {
        $result = $this->kpi->Refund_Rate();
        $rate = 0;
        if($result > 9){
            $rate = 0.85;
        } elseif($result <= 9 && $result >= 8){
            $rate = 0.9;
        } elseif($result <= 7 && $result >= 6){
            $rate = 0.95;
        } elseif($result <= 5 && $result >= 4){
            $rate = 1;
        } elseif($result <= 3){
            $rate = 1.05;
        }
        return $rate;
    }

}