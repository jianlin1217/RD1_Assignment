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
//讀取兩天的天氣資料
function get_twodayW($nowCountry)
{
    $weather = curl_init();
    curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=WeatherDescription&locationName=$nowCountry");
    curl_setopt($weather, CURLOPT_HEADER, false);
    curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

    $twoD = curl_exec($weather);
    curl_close($weather);

    return $twoD;
}
//讀取一週的天氣資料
function get_weekW($nowCountry)
{
    $weather = curl_init();
    curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&locationName=$nowCountry"); //
    curl_setopt($weather, CURLOPT_HEADER, false);
    curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

    $week = curl_exec($weather);
    curl_close($weather);

    return $week;
}
//取得雨量
function get_rain()
{
    $weather = curl_init();
    curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/C-B0025-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B"); //
    curl_setopt($weather, CURLOPT_HEADER, false);
    curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

    $rain = curl_exec($weather);
    curl_close($weather);

    return $rain;
}


$obj_w36h = json_decode(get_country());
get_twodayW("彰化縣");
get_weekW("彰化縣");
get_rain();

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
    <img class="bg" alt="">
    <div class="Slider Track topview">
        <h1>天氣觀察局</h1>
        <form action="" method="post">
            <!--選擇縣市-->
            <label for="country">縣市</label>
            <select name="country" id="country" style="background-color:royalblue; color:seashell">
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
            <label for="station">觀測站</label>
            <select name="station" id="station" style="background-color:royalblue; color:seashell">
                <option id="station0" value="none">請選擇觀測站</option>
            </select>
            <button type="submit" >資料發送ˋ3ˊ</button>
        </form>
        <script>
            $("#country").change(function() {
                if ($("#country").val() != "none") {
                    $("#countryname").text($("#country").val());
                    $("#countryImg").attr("src", "Img/" + $("#country").val() + ".jpeg");
                    $("#nowweather").css("display", "grid");
                    $("#twoday").css("display", "grid");
                    $("#week").css("display", "grid");
                } else {
                    $("#countryname").css("display", "none");
                    $("#nowweather").css("display", "none");
                    $("#twoday").css("display", "none");
                    $("#week").css("display", "none");
                }


            });
        </script>
        <h2 id="countryname"></h2>
    </div>
    <div class="wrapper topview " id="nowweather">
        <img class="country" id="countryImg" src="Img/" alt="">
        <div>
            <h3>現在天氣</h3>
        </div>
    </div>
    <div id="twoday" style="display:grid">
        <h4>未來兩天天氣</h4>
        <div class="wrappertwoday">
            <div class="sky circle">
                <p>2020-09-02 12:00:00</p>
                <p>天氣 晴</p>
                <img class="wx" src="Img/sunday.png" alt="">
                <p>舒適度 </p>
                <div class="weather">
                    <img class="temp" src="Img/Htemp.png" alt="">
                    <div>
                        <p>溫度</p>
                        <p class="precent">30ºC</p>
                    </div>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/PoP.png" alt="">
                    <div>
                        <p>降雨機率</p>
                        <p class="precent">50%</p>
                    </div>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/water.png" alt="">
                    <div>
                        <p>相對濕度</p>
                        <p class="precent">50%</p>
                    </div>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/wind3.png" alt="">
                    <div>
                        <p>西南風</p>
                        <p>平均風速1-2級(每秒2公尺)</p>
                    </div>
                </div>
            </div>
            <div class="sky circle">
            </div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
        </div>
    </div>
    <div id="week" style="display:grid">
        <h4>未來一週天氣</h4>
        <div class="wrapperweek">
            <div class="sky circle">

            </div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
        </div>
    </div>

</body>

</html>