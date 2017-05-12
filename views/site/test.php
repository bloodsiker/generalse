<?php require_once ROOT . '/views/layouts/header.php' ?>




    <iframe id="fr" style="overflow: hidden; height: 115px; width: 179px; border: 0pt none;" src="http://www.youtube.com/subscribe_widget?p=VitaliyOrekhov" scrolling="no" frameborder="0"></iframe>
    <script src="https://apis.google.com/js/platform.js"></script>

    <div class="g-ytsubscribe" data-channelid="UCakiXwkxcgI3TxZ6XHCzapA" data-layout="full" data-count="default"></div>



    <?php
$category=array(
    array("categoryID"=>1354,"name"=>"Категория 1","parent"=>"0"),
    array("categoryID"=>1707,"name"=>"Категория 2","parent"=>"1354"),
    array("categoryID"=>1708,"name"=>"Категория 3","parent"=>"1354"),
    array("categoryID"=>1710,"name"=>"Категория 5","parent"=>"1708"),
    array("categoryID"=>1711,"name"=>"Категория 6","parent"=>"1708"),
    array("categoryID"=>1709,"name"=>"Категория 4","parent"=>"0")
);

$all_category_index = [];
foreach($category as $key => $val) {
    $all_category_index[] = $val["parent"];
}

$iteration = 0;
$find_category = array();
foreach($category as $key => $val) {
    $iteration++;
    if($val["parent"] == "0") {
        $val["child"]= get_all_category($val["categoryID"], $category, array(), $all_category_index);
        $find_category[] = $val;
    }
}

//echo $iteration; // 6 запусков цикла! При 6 катогриях в списке.

echo "<pre>";
print_R($find_category);

function get_all_category($find_category, $category, $ar, $all_category_index) {
    //global $iteration;
    $k = array_search($find_category, $all_category_index);
    $count_category = count($category);

    if(strlen($k) >= 1) {

        $count_category += $k;
        for ($k; $k<$count_category; $k++){

            $val = $category[$k];


            if($val["parent"] == $find_category) {
                $val["child"] = get_all_category($val["categoryID"],$category,array(),$all_category_index);
                $ar[]=$val;
            }
            if(!isset($category[($k+1)]) || $category[($k+1)]["parent"] != $find_category) {
                break;
            }
        }
    }

    return $ar;
}
?>

<?php require_once ROOT . '/views/layouts/footer.php' ?>