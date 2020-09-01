<?php
require_once("connectDB.php");
//授權碼
$Authorization="CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B";
global $Authorization;
//存放相對應城市資源代碼
$countryCode = array(
    '宜蘭縣' => 'F-D0047-001',
    '桃園市' => 'F-D0047-005',
    '新竹縣' => 'F-D0047-009',

);

//取得縣市
function get_country()
{
    $weather = curl_init();
    curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&format=JSON&elementName=Wx&timeFrom=2020-09-02T12%3A00%3A00&timeTo=2020-09-02T12%3A00%3A00");
    curl_setopt($weather, CURLOPT_HEADER, false);
    curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

    $w36h = curl_exec($weather);
    curl_close($weather);

    return $w36h;
}
$obj_w36h = json_decode(get_country());



?>
<!DOCTYPE html>
<html lang="en">

<head Authorization="CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <title>氣象觀察站</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm   fixed-top" style=" position: fixed; width:100%;  height:60px; background-color: rgb(230, 218, 150)">
        <!-- Brand/logo -->
        <p class="navbar-brand" style="text-align:center; margin-top:20px">小雞氣象局（｡･ө･｡）</p>
    </nav>
    <?php
    ?>
    <div class="container" style="margin-top: 60px;">
        <div class="select wrapper">
            <form action="" method="post">
                <!--選擇縣市-->
                <label for="country">縣市</label>
                <select name="" id="country" style="background-color:royalblue; color:seashell">
                    <option id="country0" value="none">請選擇縣市</option>
                    <?php
                    $i = 0;
                    while ($obj_w36h->{"records"}->{"location"}[$i]->{"locationName"} != NULL) {
                    ?>
                        <option id="country<?= $i + 1 ?>" value="<?= $obj_w36h->{"records"}->{"location"}[$i]->{"locationName"} ?>"><?= $obj_w36h->{"records"}->{"location"}[$i]->{"locationName"} ?></option>

                    <?php
                        $i++;
                    }
                    ?>
                </select>
                <label for="city" style="margin-left: 50px ;">鄉鎮</label>
                <!--選擇鄉鎮-->
                <select name="" id="city" style="background-color:royalblue; color:seashell">
                    <script>
                        $("#country").change(function() {
                            alert($("#country").val());
                            <?php

                            ?>
                        });
                    </script>
                </select>
            </form>
        </div>


    </div>
</body>

</html>