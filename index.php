<?php
session_start();
require_once("connectDB.php");
//授權碼
$Authorization = "CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B";
global $Authorization;
//取得各項天氣的物件
class getWeather
{
    //取得縣市
static function get_country()
{
    $weather = curl_init();
    curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&format=JSON&elementName=Wx&timeFrom=2020-09-02T12%3A00%3A00&timeTo=2020-09-02T12%3A00%3A00");
    curl_setopt($weather, CURLOPT_HEADER, false);
    curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

    $w36h = curl_exec($weather);
    curl_close($weather);

    return $w36h;
}
    //讀取兩天的天氣資料  輸入縣市
    static function get_twodayWC($nowCountry)
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=WeatherDescription&locationName=$nowCountry");
        curl_setopt($weather, CURLOPT_HEADER, false);
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

        $twoD = curl_exec($weather);
        curl_close($weather);

        return $twoD;
    }
    //讀取兩天的天氣 不輸入縣市
    static function get_twodayW()
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=WeatherDescription");
        curl_setopt($weather, CURLOPT_HEADER, false);
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

        $twoD = curl_exec($weather);
        curl_close($weather);

        return $twoD;
    }
    //讀取一週的天氣資料
    static function get_weekWC($nowCountry)
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&locationName=$nowCountry&elementName=UVI,Wx,PoP12h,MaxT,MinT"); //
        curl_setopt($weather, CURLOPT_HEADER, false);
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

        $week = curl_exec($weather);
        curl_close($weather);

        return $week;
    }
    //讀取一週的天氣資料，不輸入縣市
    static function get_weekW()
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=UVI,Wx,PoP12h,MaxT,MinT&parameterName=CITY"); //
        curl_setopt($weather, CURLOPT_HEADER, false);
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

        $week = curl_exec($weather);
        curl_close($weather);

        return $week;
    }
    //取得雨量
    static function get_rain()
    {
        $weather = curl_init();
        curl_setopt($weather, CURLOPT_URL, "https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=HOUR_24,RAIN&parameterName=CITY"); //
        curl_setopt($weather, CURLOPT_HEADER, false);
        curl_setopt($weather, CURLOPT_RETURNTRANSFER, 1);

        $rain = curl_exec($weather);
        curl_close($weather);

        return $rain;
    }
}



//存放
$obj_w36h = json_decode(getWeather::get_country());
$obj_week = json_decode(getWeather::get_weekW());
$obj_twoday = json_decode(getWeather::get_twodayW());
$obj_rain = json_decode(getWeather::get_rain());
//   var_dump($obj_rain->{'records'}->{"location"}[1]);

//放兩天訊息進入資料庫
$i=0;
while($obj_twoday->{'records'}->{"locations"}[0]->{"location"}[$i]!=NULL)
{   
    
    $cName=$obj_twoday->{'records'}->{"locations"}[0]->{"location"}[$i]->{"locationName"}; //縣市名稱
    $allelement;
    // echo "<br>".$cName."<br>";
    //六種天氣因子存放
    $times=array();
    $pop=array();
    $wx=array();
    $t=array();
    $ci=array();
    $rh=array();
    $wind=array();


    $h=0;
    while($obj_twoday->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[0]->{'time'}[$h]!=NULL)
    {
        date_default_timezone_set("Asia/Taipei");
        $now=date("Y-m-d");
        
        // if(substr($now,))
        // var_dump($now);
        $allelement=$obj_twoday->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[0]->{'time'}[$h]->{'elementValue'}[0]->{"value"};
        //存放時間
        array_push($times,$obj_twoday->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[0]->{'time'}[$h]->{'startTime'});

       
        $element=array();
        $element=explode("。",$allelement);
        array_push($wx,$element[0]);
        array_push($pop,(int)substr($element[1],13,-1));
        array_push($t,(int)substr($element[2],12,-3));
        array_push($ci,$element[3]);
        array_push($wind,$element[4]);
        array_push($rh,(int)substr($element[5],12,-1));
        
            //資料放入資料庫
            $puttwodayDB=<<<end
            insert into twoday
            (countryName,times,wx,pop,tem,ci,wind,rh)
            values
            ("$cName","$times[$h]","$wx[$h]",$pop[$h],$t[$h],"$ci[$h]","$wind[$h]",$rh[$h]);
            end; 
            // echo $puttwodayDB;
            mysqli_query($link,$puttwodayDB);
        $h++;

}
    $i++;  
    // var_dump();
}


//放每週訊息進入資料庫
$i=0;
while($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]!=NULL)
{   
    
    $cName=$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{"locationName"}; //縣市名稱
    $times=array();
    $Pop12h=array();
    $MaxT=array();
    $MinT=array();
    $UVI=array();
    $wx=array();

   
    $h=0;
    while($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]!=NULL)
    {   
        $j=0;
        while($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]!=NULL)
        {
            // echo ($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="PoP12h");
            //雨量日期 沒觀測  就填入-1
            if((($j+1)%2)!=0&&($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="PoP12h"))
            {
                array_push($times,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'startTime'});
                if($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}==" ")
                array_push($Pop12h,-1);
                else
                array_push($Pop12h,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'});
            }
            //天氣狀況
            else if((($j+1)%2)!=0&&($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="Wx"))
            {
                array_push($wx,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'});
            }
            //最低溫度
            else if((($j+1)%2)!=0&&($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="MinT"))
            {
                if($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}==" ")
                array_push($MinT,-1);
                else
                array_push($MinT,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'});
            }
            //最高溫度
            else if ((($j+1)%2)!=0&&($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="MaxT"))
            {
                // echo $obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}."<br>";
                if($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}==" ")
                array_push($MaxT,-1);
                else
                array_push($MaxT,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'});
            }
            //紫外線
            else if(($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="UVI"))
            {
                // echo $obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}."<br>";
                if($obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'}=="")
                array_push($UVI,-1);
                else
                array_push($UVI,$obj_week->{'records'}->{"locations"}[0]->{"location"}[$i]->{'weatherElement'}[$h]->{'time'}[$j]->{'elementValue'}[0]->{'value'});
            }
            
            
            $j++;
        } 
        $h++;
        // echo "<br>";
    }
    for($w=0;$w<count($times);$w++)
    {
            // 資料放入資料庫
            $weatherWeek=<<<end
            insert into week
            (countryName,wtimes,wWx,wPop12h,wMaxT,wMinT,wUVI)
            values
            ("$cName","$times[$w]","$wx[$w]",$Pop12h[$w],$MaxT[$w],$MinT[$w],$UVI[$w]);
            end;

            // echo $weatherWeek."<br>";
            mysqli_query($link,$weatherWeek);
    }
    
    $i++;
    // echo "<br>".$cName."<br>";
    // var_dump($UVI);
}

//放觀測站、縣市、小時雨量、三小時雨量進入資料庫
$i=0;
while($obj_rain->{'records'}->{"location"}[$i]!=NULL)
{
    //觀測站名稱跟縣市名稱
    $sName=$obj_rain->{'records'}->{"location"}[$i]->{"locationName"};
    $cName=$obj_rain->{'records'}->{"location"}[$i]->{"parameter"}[0]->{"parameterValue"};
    $h=0;
    // 存入過去每小時/24小時的雨量
    $rain1H;
    $rain24H;
    $time=$obj_rain->{'records'}->{"location"}[$i]->{"time"}->{'obsTime'};
    while($obj_rain->{'records'}->{"location"}[$i]->{'weatherElement'}[$h]!=NULL)
    {
        // var_dump($obj_rain->{'records'}->{"location"}[$i]->{'weatherElement'}[0]);
        if($obj_rain->{'records'}->{"location"}[$i]->{'weatherElement'}[$h]->{'elementName'}=="RAIN")
        {
            $rain1H=$obj_rain->{'records'}->{"location"}[$i]->{'weatherElement'}[$h]->{'elementValue'};
            if($rain1H==-998)
            $rain1H=-1;
        }
        else
        {
            $rain24H=$obj_rain->{'records'}->{"location"}[$i]->{'weatherElement'}[$h]->{'elementValue'};
            if($rain24H==-998)
            $rain24H=-1;
        }
        $h++;
    }
    //資料放入雨量資料庫
    $putrainDB=<<<end
    insert into raincount
    (stationName,countryName,perHour,perDay,obsTime)
    values
    ("$sName","$cName",$rain1H,$rain24H,"$time");
    end;
    // echo $putrainDB;
    mysqli_query($link,$putrainDB);
    // echo $sName."<br>";
    // echo "每小時   ".$rain1H."過去一天   ".$rain24H."<br>";
    $i++;
}


// //放縣市進入資料庫
// $i = 0;
// while ($obj_w36h->{"records"}->{"location"}[$i]->{"locationName"} != NULL) {
//     $nowC = $obj_w36h->{"records"}->{"location"}[$i]->{"locationName"};
//     $putflag = 1;
//     //檢查有無重複
//     $picksameDB = <<<end
//     select countryName from country 
//     end;
//     $result = mysqli_query($link, $picksameDB);
//     while ($row = mysqli_fetch_assoc($result)) {
//         if ($nowC == $row['countryName']) {
//             $putflag = 0;
//         }
//     }
//     if ($putflag) {
//         $putcountryDB = <<<end
//         insert into country 
//         (countryName)
//         values
//         ("$nowC");
//         end;
//         // echo $putcountryDB;
//         mysqli_query($link, $putcountryDB);
//     }
//     $i++;
// }

if(isset($_POST['submit']))
{
    if($_POST['show']!="none")
    {
        echo "提交！！<br>縣市為".$_POST['country'].$_POST['show'];
        $_SESSION['selectcountry']=$_POST['country'];
        $_SESSION['selectshowmethod']=$_POST['show'];
        header("location: result.php");
    }
    
    
    // if(isset($_POST['winput']))
    // {
    //     echo $_POST['winput'];
    //     get_twodayW($_POST['country']);
    //     get_weekW($_POST['country']);
    // } 
    // if(isset($_POST['rinput']))
    // {
    //     echo $_POST['rinput'];
    //      get_rain();
    // }

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
    <script>
        //清除歷史避免重複送出表單
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <img class="bg" alt="">
    <div class="Slider Track topview">
        <h1>天氣觀察局</h1>
        <form action="" method="post">
            <!--選擇縣市-->
            <?php
                //從資料庫讀取縣市 及 圖片等等
            ?>
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
            <label for="show">顯示</label>
            <select name="show" id="show" style="background-color:royalblue; color:seashell">
                <option value="none">請選擇顯示什麼</option>
                <option value="showweather">天氣</option>
                <option value="showrain">雨量</option>
            </select>
            <!-- <label for="winput">天氣</label>
            <input type="checkbox" value="showweather" name="winput" id="winput">
            <label for="rinput">雨量</label>
            <input type="checkbox" value="showrain" name="rinput" id="rinput"> -->
            <button id="submit" name="submit" type="submit">資料發送ˋ3ˊ</button>
        </form>
        <?php

        ?>
        <script>
            // $("#submit").click(function() {
            //     if ($("#country").val() != "none") {
            //         $("#countryname").text($("#country").val());
            //         $("#countryImg").attr("src", "Img/" + $("#country").val() + ".jpeg");
            //         //顯示天氣
            //         if($("#winput").val()=="showweather")
            //         {
            //             $("#nowweather").css("display", "grid");
            //             $("#twoday").css("display", "grid");
            //             $("#week").css("display", "grid");
            //         }
            //         else
            //         {
            //             $("#nowweather").css("display", "none");
            //             $("#twoday").css("display", "none");
            //             $("#week").css("display", "none");
            //         }
            //         //顯示雨量
            //         if($("#winput").val()=="showrain")
            //         {
            //             $("#rain").css("display", "grid");
            //         }
            //         else
            //         {
            //             $("#rain").css("display", "none");
            //         }   
            //     } else {
            //         $("#countryname").css("display", "none");
            //         $("#nowweather").css("display", "none");
            //         $("#twoday").css("display", "none");
            //         $("#week").css("display", "none");
            //     }
            // })
            //選擇縣市即改變
            $("#country").change(function(){
                if ($("#country").val() != "none") {
                    $("#countryname").text($("#country").val());
                    $("#countryImg").attr("src", "Img/" + $("#country").val() + ".jpeg");          
                } else {
                    $("#countryname").css("display", "none");
                    $("#nowweather").css("display", "none");
                    $("#twoday").css("display", "none");
                    $("#week").css("display", "none");
                }           
            })
            //天氣或雨量
            $("#show").change(function(){
                     //顯示天氣
                    if($("#country").val() != "none")
                    {
                         if($("#show").val()=="showweather")
                        {
                            $("#nowweather").css("display", "grid");
                            $("#twoday").css("display", "grid");
                            $("#week").css("display", "grid");
                            $("#rain").css("display", "none");
                        }
                        else
                        {
                            $("#rain").css("display", "grid");
                            $("#nowweather").css("display", "none");
                            $("#twoday").css("display", "none");
                            $("#week").css("display", "none");
                        }
                     }
                    
            })
        </script>
        <h2 id="countryname"></h2>
    </div>
    <div class="wrapper topview " id="nowweather">
        <img class="country" id="countryImg" src="Img/" alt="">
        <div>
            <h3>現在天氣</h3>
            <img class="now" src="Img/thunderday.png" alt="">
            <div class="nowweather">
                <p class="now">雷雨天</p>
                <p class="now">降雨機率：30%</p>
            </div>
            <div class="nowweather">
                <p class="now">舒適度：舒適</p>
                <p class="now">溫度：26ºC</p>
            </div>
        </div>
    </div>
    <div id="twoday" style="display:none">
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
    <div id="week" style="display:none">
        <h4>未來一週天氣</h4>
        <div class="wrapperweek">
            <div class="sky circle">
                <h5>2020:09:03-12:00:00</h5>
                <div class="weather">
                    <img class="temp" src="Img/cloudsday.png" alt="">
                    <p>多雲</p>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/PoP.png" alt="">
                    <p>12小時降雨機率</p>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/Htemp.png" alt="">
                    <p>最高溫度</p>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/Ltemp.png" alt="">
                    <p>最低溫度</p>
                </div>
                <div class="weather">
                    <img class="temp" src="Img/UVI.png" alt="">
                    <p>紫外線強度</p>
                </div>
            </div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
            <div class="sky circle"></div>
        </div>
    </div>
    <div id="rain" style="display: none;">
        <h3>OO觀測站</h3>
        <h4>每小時、24小時雨量</h4>
        <div class=" rain">
            <div class="weather">
                <div class="weather sky circle" style="margin-top: 40px; margin-right: 30px">
                    <img class="wx" src="Img/water.png" alt="">
                    <h2>過去1小時雨量</h2>
                </div>
                <div class="weather sky circle" style="margin-top: 40px;">
                    <img class="wx" src="Img/water.png" alt="">
                    <h2>過去24小時雨量</h2>
                </div>
            </div>
        </div>

    </div>

</body>

</html>