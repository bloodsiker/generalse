<?php

namespace  Umbrella\app\Services\crm;

use Josantonius\Session\Session;
use Umbrella\app\User;
use Umbrella\components\Decoder;
use Umbrella\models\crm\Stocks;

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
            return $arrayProducts = array_map(function ($value) use ($user, $groupStocksNoReplaceQuantity) {

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

            return $arrayProducts = array_map(function ($value) use ($user) {
                $value['price'] = round($value['price'], 2);
                $value['stock_name'] = $this->replaceNameStockInResultTable($value['stock_name'], $user->getRole());
                return $value;
            }, $arrayProducts);
        }
    }


    /**
     * Список продуктов на складах
     *
     * @param $request
     *
     * @return array
     * @throws \Exception
     */
    public function allGoodsByClient($request)
    {
        $filters = '';

        $allGoods = [];

        if($this->user->isPartner() || $this->user->isManager()){

            if(isset($request['classifier']) && count($request['classifier']) > 0){
                $classifier = $request['classifier'];
                $classifier = implode(', ', $classifier);
                $filters .= " AND tbl_Classifier.I_D IN({$classifier})";
            }

            if(isset($request['producer']) && count($request['producer']) > 0){
                $producers = $request['producer'];
                $producers = implode(', ', $producers);
                $filters .= " AND tbl_Produsers.I_D IN({$producers})";
            }

            if(isset($request['sub_type']) && count($request['sub_type']) > 0){
                $subType = $request['sub_type'];
                $subType = Decoder::strToWindows(implode('\' , \'', $subType));
                $filters .= " AND sgt.subtype_name IN('{$subType}')";
            }

            $stocks = $request['stock'] ?? [];
            $stocks = $this->replaceNameStockInFilter($stocks, 'back_replace', $this->user->getRole());
            $id_partners = $request['id_partner'] ?? [];

            $allGoods = Decoder::arrayToUtf(Stocks::getGoodsInStocksPartners($id_partners, $stocks, $filters));
            $allGoods = $this->replaceInfoProduct($allGoods);

        } elseif ($this->user->isAdmin()){

            if(isset($request['sub_type']) && count($request['sub_type']) > 0){
                $subType = $request['sub_type'];
                $subType = Decoder::strToWindows(implode('\' , \'', $subType));
                $filters .= " AND sgt.subtype_name IN('{$subType}')";
            }

            if(isset($request['classifier']) && count($request['classifier']) > 0){
                $classifier = $request['classifier'];
                $classifier = implode(', ', $classifier);
                $filters .= " AND tbl_Classifier.I_D IN({$classifier})";
            }

            if(isset($request['producer']) && count($request['producer']) > 0){
                $producers = $request['producer'];
                $producers = implode(', ', $producers);
                $filters .= " AND tbl_Produsers.I_D IN({$producers})";
            }

            $stocks = $request['stock'] ?? [];
            $stocks = $this->replaceNameStockInFilter($stocks, 'back_replace', $this->user->getRole());
            $id_partners = $request['id_partner'] ?? [];

            $allGoods = Decoder::arrayToUtf(Stocks::getGoodsInStocksPartners($id_partners, $stocks, $filters));
            $allGoods = $this->replaceInfoProduct($allGoods);
        }

        return $allGoods;
    }

    /**
     * Проверка по складам наличия деталей
     * @param $user_id
     * @param $stocks_group
     * @param $part_number
     *
     * @return array
     * @throws \Exception
     */
    public function checkInStockAndReplaceName($user_id, $stocks_group, $part_number)
    {
        $stocks = [];
        $i = 0;

        foreach ($stocks_group as $stock){
            if(!$this->user->isPartner()){
                $product = Stocks::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetchAll');
                if(is_array($product)){
                    foreach ($product as $prodStock){
                        $stock = Decoder::strToUtf($prodStock['stock_name']);
                        $stocks[$stock] = $prodStock;
                    }
                }
            } else {
                $product = Stocks::checkGoodsInStocksPartners($user_id, $stock, $part_number, 'fetch');
                // PEX, Киев\ОК или PEX, Киев\Квазар
                if($product){
                    if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
                        || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
                        || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
                        if($product['quantity'] > 0){
                            if(isset($stocks['НОВЫЕ(UA)'])){
                                if($stocks['НОВЫЕ(UA)']['quantity'] < $product['quantity']){
                                    $stocks['НОВЫЕ(UA)'] = $product;
                                }
                            } else {
                                //$product['stock_nam'] = "НОВЫЕ(UA)";
                                $stocks['НОВЫЕ(UA)'] = $product;
                            }
                        }
                    } elseif (trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
                        || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
                        if($product['quantity'] > 0){
                            if(isset($stocks['БУ(UA)'])){
                                if($stocks['БУ(UA)']['quantity'] < $product['quantity']){
                                    $stocks['БУ(UA)'] = $product;
                                }
                            } else {
                                //$product['stock_nam'] = 'БУ(UA)';
                                $stocks['БУ(UA)'] = $product;
                            }
                        }
                    } else {
                        //$product['stock_nam'] = $stock;
                        $stocks[$stock] = $product;
                    }
                }
            }
            $i++;
        }

        // БЛИЖАЙШАЯ ПОСТАВКА
        // PEX, Киев\ОК или PEX, Киев\б/у

//        foreach ($stocks_group as $stock){
//            $product = Stocks::checkGoodsInDecompileStocksPartners($user_id, $stock, $part_number);
//            if($product){
//                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\OK')
//                    || trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\Квазар')
//                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\OK')){
//                    if($product['quantity'] > 0){
//                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'])){
//                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ']['quantity'] < $product['quantity']){
//                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
//                            }
//                        } else {
//                            //$product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ';
//                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - НОВЫЕ'] = $product;
//                        }
//                    }
//                }
//
//                if(trim($product['stock_name']) == Decoder::strToWindows('PEX, Киев\б/у')
//                    || trim($product['stock_name']) == Decoder::strToWindows('KVAZAR, Киев\б/у')){
//                    if($product['quantity'] > 0){
//                        if(isset($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'])){
//                            if($stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ']['quantity'] < $product['quantity']){
//                                $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
//                            }
//                        } else {
//                            //$product['stock_nam'] = 'БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ';
//                            $stocks['БЛИЖАЙШАЯ ПОСТАВКА (2 дня) - БУ'] = $product;
//                        }
//                    }
//                }
//            }
//            $i++;
//        }
        return $stocks;
    }



    /**
     * Подмена названий складов в результатирующей таблице
     * @param $stockName
     * @param string $role
     * @return null|string
     */
    public function replaceNameStockInResultTable($stockName, $role = 'administrator')
    {
        if($role == 'partner'){
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

        if($role == 'partner') {
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


    /**
     * Replace stockName in Returns
     * @param $stock
     *
     * @return mixed
     */
    public function replaceStock($stock)
    {
        $stocksReplace = [
            ['Electrolux' => 'OK (KVAZAR)'],
            ['БУ(UA)' => ['KVAZAR, Киев\б/у', 'PEX, Киев\б/у']],
            ['НОВЫЙ(UA)' => ['KVAZAR, Киев\OK', 'PEX, Киев\OK', 'PEX, Киев\Квазар']]
        ];

        foreach ($stocksReplace as $stocks){
            if(array_key_exists($stock, $stocks)){
                $stocksOrArray = $stocks[$stock];
                if(is_array($stocksOrArray)){
                    $rand_keys = array_rand($stocksOrArray, 1);
                    $stockName = $stocksOrArray[$rand_keys];
                } else {
                    $stockName = $stocks[$stock];
                }
                break;
            } else {
                $stockName = $stock;
            }
        }
        return $stockName;
    }
}