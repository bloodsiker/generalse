<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Currency
{

    /**
     * Последний актуальный курс
     * @param $currency
     *
     * @return mixed
     */
    public static function getRatesCurrency($currency)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1
                  tbl_Rate.DateRate,
                  tbl_Rate.OutputRate,
                  tbl_Curency.ShortName
                FROM tbl_Rate
                  INNER JOIN tbl_Curency
                    ON tbl_Rate.CurencyID = tbl_Curency.Number
                WHERE tbl_Curency.ShortName = :currency
                ORDER BY tbl_Rate.Number DESC";

        $result = $db->prepare($sql);
        $result->bindParam(':currency', $currency, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Курс по заданной дате
     * @param $currency
     * @param $day
     *
     * @return mixed
     */
    public static function getRatesCurrencyPerDay($currency, $day)
    {
        $db = MsSQL::getConnection();

        $sql = "SELECT TOP 1
                  tbl_Rate.DateRate,
                  tbl_Rate.OutputRate,
                  tbl_Curency.ShortName
                FROM tbl_Rate
                  INNER JOIN tbl_Curency
                    ON tbl_Rate.CurencyID = tbl_Curency.Number
                WHERE tbl_Curency.ShortName = :currency
                AND tbl_Rate.DateRate = :per_day";

        $result = $db->prepare($sql);
        $result->bindParam(':currency', $currency, PDO::PARAM_STR);
        $result->bindParam(':per_day', $day, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Prices by goodID
     * @param $goodsID
     *
     * @return mixed
     */
    public static function getPartnersCurrencyByGoodsID($goodsID)
    {
        $db = MsSQL::getConnection();

        $sql = "select
               tbl_GoodsNames.i_d --номер товара
               ,PriceR.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short())as rozn_new --розница
               ,PriceRBU.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as rozn_used --розница б/у
               ,PriceP.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as partner_new -- партнер
               ,PricePBU.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as partner_used -- парнер б/у
               ,PriceO.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as opt_new -- оптовик
               ,PriceOBU.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as opt_used -- отптовик б/у
               ,PriceV.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as vip_new -- вип
               ,PriceVBU.price * dbo.ufn_Curencys_Rate_Output_Cross(1, tbl_Produsers.curency_id, 1, dbo.ufn_Date_Current_Short()) as vip_used -- вип б/у
        from tbl_GoodsNames
                     inner join tbl_Produsers
                            on tbl_Produsers.i_d = tbl_GoodsNames.produserID
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 1
                     )PriceR
                     on PriceR.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 15
                     )PriceRBU
                     on PriceRBU.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 5
                     )PriceP
                     on PriceP.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 20
                     )PricePBU
                     on PricePBU.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 18
                     )PriceO
                     on PriceO.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 23
                     )PriceOBU
                     on PricePBU.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 6
                     )PriceV
                     on PriceV.goodsNameID = tbl_GoodsNames.i_d
               left outer join
                     (select
                            tbl_ABCDPrices.goodsNameID
                            ,convert(float, tbl_ABCDPrices.price) / 100 as price
                     from tbl_ABCDPrices with (nolock)
                     where
                            tbl_ABCDPrices.depatmentID = 1
                            and tbl_ABCDPrices.namePriceID = 22
                     )PriceVBU
                     on PriceVBU.goodsNameID = tbl_GoodsNames.i_d
        where
               tbl_GoodsNames.i_d = :id";

        $result = $db->prepare($sql);
        $result->bindParam(':id', $goodsID, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}