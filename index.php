<?php
    require_once("connectDB.php");
    //取得36h天氣，縣市
    function get_weather36h()
    {
        $weather=curl_init();
        curl_setopt($weather,CURLOPT_URL,"https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&format=JSON");
        curl_setopt($weather,CURLOPT_HEADER,false);
        curl_setopt($weather,CURLOPT_RETURNTRANSFER,1);

        $w36h=curl_exec($weather);
        curl_close($weather);

        return $w36h;
    }
      $obj_w36h=json_decode(get_weather36h());
      echo ($obj_w36h->{"records"}->{"location"}[0]->{"locationName"});
    //    echo $obj_w36h->{'result'}->{"records"}->{"location"}[0]->{"locationName"};
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
<nav class="navbar navbar-expand-sm bg-secondary navbar-dark fixed-top" style=" position: fixed; width:100%;  height:60px;">
    <!-- Brand/logo -->
    <p class="navbar-brand" style="text-align:center; margin-top:20px">小雞氣象局（｡･ө･｡）</p>
</nav>
    <?php
    ?>
    <div class="container" style="margin-top: 60px;">
            <div  class="select">
                <form action="" method="post">
                    <label for="country">縣市</label>
                    <select name="" id="country">
                        <?php
                            $i=0;
                            while($obj_w36h->{"records"}->{"location"}[$i]->{"locationName"}!=NULL){
                        ?>
                        <option value=""><?=$obj_w36h->{"records"}->{"location"}[$i]->{"locationName"}?></option>

                        <?php
                            $i++;
                            }
                        ?>
                    </select>
                    <label for="city">鄉鎮</label>
                    <select name="" id="city">
                            
                    </select>
                </form>
            </div>
            

    </div>
   
</body>

</html>