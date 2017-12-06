<?php

namespace  Umbrella\app\Services\crm;

use Josantonius\Session\Session;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\Stocks;

class StockService
{

    private $user;

    /**
     * StockService constructor.
     */
    public function __construct()
    {
        $this->user = new User(Session::get('user'));
    }


    /**
     * Replace quantity product
     * @param $arrayProducts
     * @return array
     */
    public function replaceInfoProduct($arrayProducts)
    {
        $user = $this->user;

        $groupStocksNoReplaceQuantity = [
            'Lenovo'        => ['BAD', 'Not Used', 'Restored', 'Dismantling', 'Local Source', 'SWAP'],
            'Electrolux'    => ['OK (Выборгская, 104)', 'OK (KVAZAR)'],
            'GE'            => ['OK'],
            'Lenovo ПСР'    => [],
            'UKRAINE OOW'   => []
        ];

        if($user->isPartner()){
            return $arrayProducts = array_map(function ($value) use($user, $groupStocksNoReplaceQuantity) {

                foreach ($groupStocksNoReplaceQuantity as $group => $stocks){
                    if($user->getGroupName() == $group){
                        if(!in_array($value['stock_name'], $stocks)){
                            if ($value['quantity'] <= 5) {
                                $value['quantity'] = 'Заканчивается';
                            } else {
                                $value['quantity'] = 'На складе';
                            }
                        }
                    }
                }

                $value['stock_name'] = $this->replaceNameStockInResultTable($value['stock_name'], $user->getRole());
                $value['price'] = round($value['price'], 2);
                return $value;
            }, $arrayProducts);
        } else {

            return $arrayProducts = array_map(function ($value) use($user) {
                $value['price'] = round($value['price'], 2);
                $value['stock_name'] = $this->replaceNameStockInResultTable($value['stock_name'], $user->getRole());
                return $value;
            }, $arrayProducts);
        }
    }


    /**
     * Список продуктов на складах
     * @param $request
     * @return array
     */
    public function allGoodsByClient($request)
    {
        $filters = '';

        $allGoods = [];

        if($this->user->isPartner() || $this->user->isManager()){

            if(isset($request['sub_type']) && sizeof($request['sub_type']) > 0){
                $subType = $request['sub_type'];
                $subType = Decoder::strToWindows(implode('\' , \'', $subType));
                $filters .= " AND subtype_name IN('{$subType}')";
            }

            $stocks =  isset($request['stock']) ? $request['stock'] : [];
            $stocks = $this->replaceNameStockInFilter($stocks, 'back_replace', $this->user->getRole());
            $id_partners = isset($_REQUEST['id_partner']) ? $request['id_partner'] : [];

            $allGoods = Decoder::arrayToUtf(Stocks::getGoodsInStocksPartners($id_partners, $stocks, $filters));
            $allGoods = $this->replaceInfoProduct($allGoods);

        } elseif ($this->user->isAdmin()){

            if(isset($request['sub_type']) && sizeof($request['sub_type']) > 0){
                $subType = $request['sub_type'];
                $subType = Decoder::strToWindows(implode('\' , \'', $subType));
                $filters .= " AND subtype_name IN('{$subType}')";
            }

            $stocks =  isset($request['stock']) ? $request['stock'] : [];
            $stocks = $this->replaceNameStockInFilter($stocks, 'back_replace', $this->user->getRole());
            $id_partners = isset($request['id_partner']) ? $request['id_partner'] : [];

            $allGoods = Decoder::arrayToUtf(Stocks::getGoodsInStocksPartners($id_partners, $stocks, $filters));
            $allGoods = $this->replaceInfoProduct($allGoods);
        }

        return $allGoods;
    }



    /**
     * Подмена названий складов в результатирующей таблице
     * @param $stockName
     * @param string $role
     * @return null|string
     */
    public function replaceNameStockInResultTable($stockName, $role = 'administrator')
    {
        if($role != 'administrator'){
            if($stockName == 'KVAZAR, Киев\OK'
                || $stockName == 'PEX, Киев\OK'
                || $stockName == 'PEX, Киев\Квазар'){
                $stockReplace = 'НОВЫЙ(UA)';
            } elseif($stockName == 'KVAZAR, Киев\б/у'
                || $stockName == 'PEX, Киев\б/у') {
                $stockReplace = 'БУ(UA)';
            } elseif ($stockName == 'OK (Выборгская, 104)' || $stockName == 'OK (KVAZAR)'){
                $stockReplace = 'Electrolux';
            } else {
                $stockReplace = $stockName;
            }
        } else {
            $stockReplace = $stockName;
        }

        return $stockReplace;
    }


    /**
     * Подмена названий складов в фильтрации
     * @param $arrayStocks
     * @param string $replace
     * @param string $role
     * @return array
     */
    public function replaceNameStockInFilter($arrayStocks, $replace = 'replace', $role = 'administrator')
    {
        $newArrayStocks = [];

        if($role != 'administrator') {
            if($replace == 'replace'){
                if(is_array($arrayStocks)){
                    foreach ($arrayStocks as $stock){
                        if($stock == 'KVAZAR, Киев\OK'
                            || $stock == 'PEX, Киев\OK'
                            || $stock == 'PEX, Киев\Квазар'){
                            $newArrayStocks[] = 'НОВЫЙ(UA)';
                        } elseif($stock == 'KVAZAR, Киев\б/у'
                            || $stock == 'PEX, Киев\б/у') {
                            $newArrayStocks[] = 'БУ(UA)';
                        } elseif ($stock == 'OK (Выборгская, 104)' || $stock == 'OK (KVAZAR)'){
                            $newArrayStocks[] = 'Electrolux';
                        } else {
                            $newArrayStocks[] = $stock;
                        }
                    }
                }
            } elseif ($replace == 'back_replace'){
                if(is_array($arrayStocks)){
                    foreach ($arrayStocks as $stock){
                        if($stock == 'НОВЫЙ(UA)'){
                            $newArrayStocks[] = 'KVAZAR, Киев\OK';
                            $newArrayStocks[] = 'PEX, Киев\OK';
                            $newArrayStocks[] = 'PEX, Киев\Квазар';
                        } elseif($stock == 'БУ(UA)') {
                            $newArrayStocks[] = 'KVAZAR, Киев\б/у';
                            $newArrayStocks[] = 'PEX, Киев\б/у';
                        } elseif ($stock == 'Electrolux'){
                            $newArrayStocks[] = 'OK (Выборгская, 104)';
                            $newArrayStocks[] = 'OK (KVAZAR)';
                        } else {
                            $newArrayStocks[] = $stock;
                        }
                    }
                }
            }

        } else {
            $newArrayStocks = $arrayStocks;
        }
        return array_unique($newArrayStocks);
    }
}