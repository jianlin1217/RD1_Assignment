
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
        <form action="" method="post" target="send-iframe">
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