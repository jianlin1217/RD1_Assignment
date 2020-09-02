<?php
require_once("connectDB.php");
//授權碼
$Authorization = "CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B";
global $Authorization;
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
//放縣市進入資料庫
$i = 0;
while ($obj_w36h->{"records"}->{"location"}[$i]->{"locationName"} != NULL) {
    $nowC = $obj_w36h->{"records"}->{"location"}[$i]->{"locationName"};
    $putflag = 1;
    //檢查有無重複
    $picksameDB = <<<end
    select countryName from country 
    end;
    $result = mysqli_query($link, $picksameDB);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($nowC == $row['countryName']) {
            $putflag = 0;
        }
    }
    if ($putflag) {
        $putcountryDB = <<<end
        insert into country 
        (countryName)
        values
        ("$nowC");
        end;
        // echo $putcountryDB;
        mysqli_query($link, $putcountryDB);
    }
    $i++;
}




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
    <div class="Slider Track topview">
        <h1>天氣觀察局</h1>
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
                <!-- <label for="city" style="margin-left: 50px ;">鄉鎮</label>
                <select name="" id="city" style="background-color:royalblue; color:seashell">
                    
                </select> -->
                <script>
                    $("#country").change(function() {
                        // alert($("#country").val());
                        // alert("");
                        <?php
                            //查詢選擇縣市
                        ?>
                        $("#countryname").text($("#country").val());
                        $("#countryImg").attr("src", "Img/" + $("#country").val() + ".jpeg");
                        $("#nowweather").css("display","grid");
                    });
                </script>
            </form>
        <!-- <img src="Img/高雄市月世界.jpeg" alt="圖片錯誤ＱＡＯ"> -->
        <h2 id="countryname"></h2>
    </div>
    <div class="wrapper topview " id="nowweather" >
        <img id="countryImg" src="Img/" alt="">
        <div>
            <h3>現在天氣</h3>
        </div>
    </div>
    <h4>未來兩天天氣</h4>
    <div class="wrappertwoday" style="margin-top: 60px;">
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
    </div>
    <h4>未來一週天氣</h4>
    <div class="wrapperweek">
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
    </div>
</body>

</html>