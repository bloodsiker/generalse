<?php

namespace Umbrella\models;

use PDO;
use Umbrella\components\Db\MsSQL;

class Price
{

    /**
     * return all Prices
     *
     * @return array
     */
    public static function getAllPriceMsSQL()
    {
        $db = MsSQL::getConnection();

        $sql = 'select top 50
                    tbl_GoodsNames.PartNumber as PartNumber
                    ,tbl_GoodsNames.mName as mName
                    ,tbl_Classifier.mName as class_name
                    ,GoodsSubType.shortName as subType
                    ,tbl_Produsers.shortName as producer
                    ,tbl_GoodsTypes.mName as goodsType
                    ,convert(float, tbl_ABCDPrices_RoznNew.price) / 100 as roz
                    ,convert(float, tbl_ABCDPrices_RoznBu.price) / 100 as rozBu
                    ,convert(float, tbl_ABCDPrices_Partner.price) / 100 as partner
                    ,convert(float, tbl_ABCDPrices_PartnerBu.price) / 100 as partnerBu
                    ,convert(float, tbl_ABCDPrices_Opt.price) / 100 as opt
                    ,convert(float, tbl_ABCDPrices_OptBu.price) / 100 as optBu
                    ,convert(float, tbl_ABCDPrices_Vip.price) / 100 as vip
                    ,convert(float, tbl_ABCDPrices_VipBu.price) / 100 as vipBu
                from tbl_GoodsNames with (nolock)
            
                    left outer join tbl_Classifier with (nolock)
                        on tbl_Classifier.i_d = tbl_GoodsNames.classifierID
                    left outer join GoodsSubType with (nolock)
                        on GoodsSubType.id = tbl_GoodsNames.subType
                    left outer join tbl_Produsers with (nolock)
                        on tbl_Produsers.i_d = tbl_GoodsNames.produserID
                    left outer join tbl_GoodsTypes with (nolock)
                        on tbl_GoodsTypes.i_d = tbl_GoodsNames.goodsTypeID
                
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_RoznNew with (nolock)
                        on tbl_ABCDPrices_RoznNew.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_RoznNew.depatmentID = 1
                        and tbl_ABCDPrices_RoznNew.namePriceID = 1
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_RoznBu with (nolock)
                        on tbl_ABCDPrices_RoznBu.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_RoznBu.depatmentID = 1
                        and tbl_ABCDPrices_RoznBu.namePriceID = 15
                
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_Partner with (nolock)
                        on tbl_ABCDPrices_Partner.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_Partner.depatmentID = 1
                        and tbl_ABCDPrices_Partner.namePriceID = 5
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_PartnerBu with (nolock)
                        on tbl_ABCDPrices_PartnerBu.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_PartnerBu.depatmentID = 1
                        and tbl_ABCDPrices_PartnerBu.namePriceID = 20
            
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_Opt with (nolock)
                        on tbl_ABCDPrices_Opt.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_Opt.depatmentID = 1
                        and tbl_ABCDPrices_Opt.namePriceID = 18
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_OptBu with (nolock)
                        on tbl_ABCDPrices_OptBu.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_OptBu.depatmentID = 1
                        and tbl_ABCDPrices_OptBu.namePriceID = 23
            
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_Vip with (nolock)
                        on tbl_ABCDPrices_Vip.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_Vip.depatmentID = 1
                        and tbl_ABCDPrices_Vip.namePriceID = 6
                    left outer join tbl_ABCDPrices tbl_ABCDPrices_VipBu with (nolock)
                        on tbl_ABCDPrices_VipBu.goodsNameID = tbl_GoodsNames.i_d
                        and tbl_ABCDPrices_VipBu.depatmentID = 1
                        and tbl_ABCDPrices_VipBu.namePriceID = 22
                where
                    (isnull(convert(float, tbl_ABCDPrices_RoznNew.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_RoznBu.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_Partner.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_PartnerBu.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_Opt.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_OptBu.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_Vip.price) / 100, 0) != 0
                    or
                    isnull(convert(float, tbl_ABCDPrices_VipBu.price) / 100, 0) != 0)
                order by
                    tbl_GoodsNames.PartNumber 
                    ,tbl_GoodsNames.mName';

        $result = $db->prepare($sql);
        $all = $result->fetchAll(PDO::FETCH_ASSOC);
        return $all;
    }
}