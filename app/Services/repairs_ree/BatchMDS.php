<?php

namespace Umbrella\app\Services\repairs_ree;

use Carbon\Carbon;
use Umbrella\app\User;
use Umbrella\components\Decoder;

class BatchMDS
{
    private $arrayData = [];

    private $excelToArray = [];
    /**
     * @var User
     */
    private $user;

    public function __construct($arrayData, User $user)
    {
        $this->arrayData = array_splice($arrayData, 1);
        $this->unsetEmptyExcelArray();
        $this->user = $user;
    }


    /**
     * @return array
     */
    public function unsetEmptyArray() :array
    {
        foreach ($this->excelToArray as $key => $value){

            $this->excelToArray[$key] = array_diff($value, array('', null));
        }
        return $this->excelToArray;
    }


    /**
     * unset empty array elements
     */
    public function unsetEmptyExcelArray()
    {
        foreach ($this->arrayData as $key => $value){

            $count = count($value);
            $i = 0;
            foreach ($value as $val){
                if(empty($val)){
                    $i++;
                }
            }
            if($count == $i){
               unset($this->arrayData[$key]) ;
            }
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function excelToArray() :array
    {
        $array = [];
        $i = 0;
        foreach ($this->arrayData as $value){
            $array[$i]['site_account_id'] = $this->user->getId();
            $array[$i]['PartnerJobOrder'] = $value['A'];
            $array[$i]['SOStatus'] = $value['B'];
            $array[$i]['Brand'] = $value['C'];
            $array[$i]['IMEIorSN'] = $value['D'];
            $array[$i]['IMEI2'] = $value['E'];
            $array[$i]['SN'] = $value['F'];
            $array[$i]['MTM'] = $value['G'];
            $array[$i]['PurchaseDate'] = $value['I'];
            $array[$i]['SOWarrantyStatus'] = $value['J'];
            $array[$i]['FirstName'] = !empty($value['K']) ? Decoder::strToWindows($value['K']) : null;
            $array[$i]['LastName'] = !empty($value['K']) ? Decoder::strToWindows($value['L']) : null;
            $array[$i]['Address'] = $value['M'];
            $array[$i]['provinceORstate'] = $value['N'];
            $array[$i]['City'] = $value['O'];
            $array[$i]['PostCode'] = $value['P'];
            $array[$i]['Phone'] = $value['Q'];
            $array[$i]['AlternatePhone'] = $value['R'];
            $array[$i]['Email'] = $value['S'];
            $array[$i]['SocialMediaType'] = $value['T'];
            $array[$i]['SocialMediaAccount'] = $value['U'];
            $array[$i]['ServiceType'] = $value['V'];
            $array[$i]['TrackingNumberIn'] = $value['W'];

            //$array[$i]['CarryInTime'] = !empty($value['AA']) ? Carbon::parse($value['AA'])->format('Y.m.d H:i') : null;
            $array[$i]['CarryInTime'] = $value['AA'];
            $array[$i]['ComplaintCode'] = $value['AC'];
            $array[$i]['ServiceNotes'] = $value['AD'];
            $array[$i]['Earphone'] = $value['AE'];
            $array[$i]['Battery'] = $value['AF'];
            $array[$i]['Charger'] = $value['AG'];
            $array[$i]['OtherAccessories'] = $value['AH'];
            $array[$i]['AirtimeCarrierCode'] = $value['AJ'];
            $array[$i]['APCCode'] = $value['H'];
            $array[$i]['DOA'] = $value['AB'];
            $array[$i]['CID'] = $value['Z'];
            $array[$i]['DOA1'] = $value['AK'];
            $array[$i]['CID1'] = $value['AL'];
            $array[$i]['RepairType'] = $value['AM'];
            $array[$i]['PrimaryProblemCode'] = $value['AN'];
            $array[$i]['SecondaryProblemCode'] = $value['AO'];
            $array[$i]['PrimaryRepairCode'] = $value['AP'];
            $array[$i]['SecondaryRepairCode'] = $value['AQ'];
            $array[$i]['LSTReferenceID'] = $value['AS'];
            $array[$i]['TransactionCode'] = $value['AR'];

            $array[$i]['DefectivePartNumber1'] = $value['BC'];
            $array[$i]['ReplacedPartNumber1'] = $value['BD'];
            $array[$i]['QuantityofPart1'] = $value['BE'];
            $array[$i]['PartsOrder1'] = $value['BF'];
            $array[$i]['PartStatusofPart1'] = $value['BG'];

            $array[$i]['DefectivePartNumber2'] = $value['BJ'];
            $array[$i]['ReplacedPartNumber2'] = $value['BK'];
            $array[$i]['QuantityofPart2'] = $value['BL'];
            $array[$i]['PartsOrder2'] = $value['BM'];
            $array[$i]['PartStatusofPart2'] = $value['BN'];

            $array[$i]['DefectivePartNumber3'] = $value['BQ'];
            $array[$i]['ReplacedPartNumber3'] = $value['BR'];
            $array[$i]['QuantityofPart3'] = $value['BS'];
            $array[$i]['PartsOrder3'] = $value['BT'];
            $array[$i]['PartStatusofPart3'] = $value['BU'];

            $array[$i]['DefectivePartNumber4'] = $value['BX'];
            $array[$i]['ReplacedPartNumber4'] = $value['BY'];
            $array[$i]['QuantityofPart4'] = $value['BZ'];
            $array[$i]['PartsOrder4'] = $value['CA'];
            $array[$i]['PartStatusofPart4'] = $value['CB'];

            $array[$i]['DefectivePartNumber5'] = $value['CE'];
            $array[$i]['ReplacedPartNumber5'] = $value['CF'];
            $array[$i]['QuantityofPart5'] = $value['CG'];
            $array[$i]['PartsOrder5'] = $value['CH'];
            $array[$i]['PartStatusofPart5'] = $value['CI'];

            $array[$i]['DefectivePartNumber6'] = $value['CL'];
            $array[$i]['ReplacedPartNumber6'] = $value['CM'];
            $array[$i]['QuantityofPart6'] = $value['CN'];
            $array[$i]['PartsOrder6'] = $value['CO'];
            $array[$i]['PartStatusofPart6'] = $value['CP'];

            $array[$i]['DefectivePartNumber7'] = $value['CS'];
            $array[$i]['ReplacedPartNumber7'] = $value['CT'];
            $array[$i]['QuantityofPart7'] = $value['CU'];
            $array[$i]['PartsOrder7'] = $value['CV'];
            $array[$i]['PartStatusofPart7'] = $value['CW'];

            $array[$i]['DefectivePartNumber8'] = $value['CZ'];
            $array[$i]['ReplacedPartNumber8'] = $value['DA'];
            $array[$i]['QuantityofPart8'] = $value['DB'];
            $array[$i]['PartsOrder8'] = $value['DC'];
            $array[$i]['PartStatusofPart8'] = $value['DD'];

            $array[$i]['DefectivePartNumber9'] = $value['DG'];
            $array[$i]['ReplacedPartNumber9'] = $value['DH'];
            $array[$i]['QuantityofPart9'] = $value['DI'];
            $array[$i]['PartsOrder9'] = $value['DJ'];
            $array[$i]['PartStatusofPart9'] = $value['DK'];

            $array[$i]['DefectivePartNumber10'] = $value['DN'];
            $array[$i]['ReplacedPartNumber10'] = $value['DO'];
            $array[$i]['QuantityofPart10'] = $value['DP'];
            $array[$i]['PartsOrder10'] = $value['DQ'];
            $array[$i]['PartStatusofPart10'] = $value['DR'];

            $array[$i]['NewIMEI1'] = $value['AT'];
            $array[$i]['NewIMEI2'] = $value['AU'];
            $array[$i]['NewSN'] = $value['AV'];
            $array[$i]['NewSoftwareVersion'] = $value['AW'];
            $array[$i]['PartStatus'] = null; ////////////
            $array[$i]['RepairFinishiTime'] = !empty($value['AX']) ? Carbon::parse($value['AX'])->format('Y-m-d H:i') : null;
            $array[$i]['TrackingNumberOut'] = $value['AY'];
            $array[$i]['PickupTime'] = !empty($value['BB']) ? Carbon::parse($value['BB'])->format('Y-m-d H:i') : null;

            $i++;
        }
        $this->excelToArray = $array;

        return $this->unsetEmptyArray();
    }
}