<?php
require_once("connectDB.php");
session_start();
$selectCountry = $_SESSION['selectcountry'];
$selectShow = $_SESSION['selectshowmethod'];
// echo $selectCountry . "<br>" . $selectShow;

//重新選擇縣市
if(isset($_POST['submit']))
{
    $_SESSION['selectcountry']=$_POST['country'];
    $_SESSION['selectshowmethod']=$_POST['show'];
    header("location: result.php");
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
    <a class="top" href="#title">top</a>
    <img class="bg" alt="">
    <div class="Slider Track topview">
        <h1 id="title">天氣觀察局</h1>
        <form action="" method="post">
            <!--選擇縣市-->
            <label for="country">縣市</label>
            <select name="country" id="country" style="background-color:royalblue; color:seashell">
                <option id="country0" value="none">請選擇縣市</option>
                <?php
                $i = 0;
                $getallcountry = <<<end
                select countryName from country
                end;
                $resultN = mysqli_query($link, $getallcountry);
                while ($row = (mysqli_fetch_assoc($resultN))) {
                ?>
                    <option id="country<?= $i + 1 ?>" value="<?= $row['countryName'] ?>"><?= $row['countryName'] ?></option>
                <?php
                    $i++;
                }
                ?>
            </select>
            <!-- 顯示 -->
            <label for="show">顯示</label>
            <select name="show" id="show" style="background-color:royalblue; color:seashell">
                <option value="none">請選擇顯示什麼</option>
                <option value="showweather">天氣</option>
                <option value="showrain">雨量</option>
            </select>
            <button id="submit" name="submit" type="submit">資料發送ˋ3ˊ</button>
        </form>
        <?php
            //若是觀察站可以選擇
            if($selectShow=="showrain")
            {
                $station=array();
                $getsName=<<<end
                select DISTINCT stationName from raincount where countryName="$selectCountry";
                end;
                // echo $getsName;
                $sresult=mysqli_query($link,$getsName);
                while($row=mysqli_fetch_assoc($sresult))
                {
                    array_push($station,$row['stationName']);
                    ?>
                    <a href="#<?=$row['stationName']?>"><?=$row['stationName']?></a>
                    <?php
                }
            }
        ?>
        <h2 id="countryname"><?= $selectCountry ?></h2>
    </div>
    <?php
    //取得現在日期
    date_default_timezone_set("Asia/Taipei");
    $now=date("Y-m-d H:m:s");
    $next=date("Y-m-d",strtotime('+1 days'));
    $next2=date("Y-m-d",strtotime('+3 days'));
    // echo $now ;

    //看選擇方式抓資料
    if ($selectShow == "showweather") {
        //從資料庫中取得資料
        $gettwodaydata = <<<end
        select times,wx,pop,tem,ci,wind,rh
        from twoday  where countryName = "$selectCountry" and times between "$next" and "$next2";
        end;
        // echo $gettwodaydata;
        $resultT = mysqli_query($link,$gettwodaydata);
    ?>
        <div class="weather topview " id="nowweather">
            <img class="country" id="countryImg" src="Img/<?=$selectCountry?>.jpeg" alt="">
            <div>
                <h3>現在天氣</h3>
            </div>
        </div>
        <div id="twoday" style="display:grid">
            <h4>未來兩天天氣</h4>
            <div class="wrappertwoday">
                <?php
                while($row=mysqli_fetch_assoc($resultT)){
                    // echo $row['times']."<br>";
                    // echo date("H",strtotime($row['times']))."<br>";
                    if(date("H",strtotime($row['times']))!="6"&&date("H",strtotime($row['times']))!="12"&&date("H",strtotime($row['times']))!="18")
                    continue;
                ?>
                <div class="sky circle">
                    <p><?=$row['times']?></p>
                    <p>天氣 <?=$row['wx']?></p>
                    <img class="wx" src="Img/sunday.png" alt="">
                    <p>舒適度 <?=$row['ci']?></p>
                    <div class="weather">
                        <img class="temp" src="Img/Htemp.png" alt="">
                        <div>
                            <p>溫度</p>
                            <p class="precent"><?=$row['tem']?>ºC</p>
                        </div>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/PoP.png" alt="">
                        <div>
                            <p>降雨機率</p>
                            <p class="precent"><?=$row['pop']?>%</p>
                        </div>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/water.png" alt="">
                        <div>
                            <p>相對濕度</p>
                            <p class="precent"><?=$row['rh']?>%</p>
                        </div>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/wind3.png" alt="">
                        <div>
                            <p><?=substr($row['wind'],0,9) ?></p>
                            <p><?=substr($row['wind'],9)?></p>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>

        <div id="week" style="display:grid">
            <h4>未來一週天氣</h4>
            <div class="wrapperweek">
                <?php
                    //取得一週天氣
                    $nowday=date("Y-m-d");
                    $nextweek=date("Y-m-d",strtotime("+7 days"));
                    // echo $nextweek;
                    $getweek=<<<end
                    select wtimes,wWx,wPop12h,wMaxT,wMinT,wUVI from week where countryName="$selectCountry" and wtimes between "$nowday" and "$nextweek";
                    end;
                    // echo $getweek;
                    $resultW=mysqli_query($link,$getweek);
                    while($row=mysqli_fetch_assoc($resultW))
                    {
                        
                ?>
                <div class="sky circle">
                    <h5><?= $row['wtimes']?></h5>
                    <div class="weather">
                        <img class="temp" src="Img/cloudsday.png" alt="">
                        <p><?= $row['wWx']?></p>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/PoP.png" alt="">
                        <p>12小時降雨機率 
                        <?php
                        if($row['wPop12h']==-1)
                        echo "--";
                        else
                        echo $row['wPop12h'];
                        ?></p>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/Htemp.png" alt="">
                        <p>最高溫度 <?=$row['wMaxT']?></p>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/Ltemp.png" alt="">
                        <p>最低溫度 <?=$row['wMinT']?></p>
                    </div>
                    <div class="weather">
                        <img class="temp" src="Img/UVI.png" alt="">
                        <p>紫外線強度 <?=$row['wUVI']?></p>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>

    <?php

    } else 
    {
        ?>
        <?php
        //從雨量資料庫拿資料
        $getrain=<<<end
        select  obsTime,perHour,perDay,stationName from raincount where countryName="$selectCountry" and obsTime>"$nowday";
        end;
        // echo $getrain;
        $resultR=mysqli_query($link,$getrain);
        ?>
        <div id="rain" style="display: grid;">
        
        <?php
        $i=0;
        $flag=1;
        while($row=mysqli_fetch_assoc($resultR))
        {
            if(($station[$i]==$row['stationName'])&&$flag)
            {
                $flag=0;
                
                ?>
                <h3 id="<?=$station[$i]?>"><?=$station[$i]?> 觀測站</h3>
                <?php
                $i++;
            }
            else 
            {
                $flag=1;
            }
        ?>
        <h2>觀察時間 <?=$row['obsTime']?></h2>
        <div class=" rain">
            <div class="weather">
                <div class="showrain sky circle" style="margin-top: 40px; margin-right: 30px">
                    <img class="wx" src="Img/water.png" alt="">
                    <p class="rain">過去1小時雨量   
                        <?php
                        if($row['perHour']==-1)
                        echo "--";
                        else
                        echo $row['perHour'];
                        ?> 
                    </p>
                </div>
                <div class="showrain sky circle" style="margin-top: 40px;">
                    <img class="wx" src="Img/water.png" alt="">
                     <p class="rain">過去24小時雨量 
                         <?php
                        if($row['perDay']==-1)
                        echo "--";
                        else
                        echo $row['perDay'];
                        ?>  
                        </p> 
                </div>
            </div>
        </div>
    </div>  
        <?php
        }
    }

    ?>

</body>

</html>