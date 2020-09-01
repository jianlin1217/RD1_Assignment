<!DOCTYPE html>
<html lang="en">

<head Authorization="CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>氣象觀察站</title>
</head>

<body>
    <?php
    function get_weather36h()
    {
        $weather=curl_init();
        curl_setopt($weather,CURLOPT_URL,"https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&format=JSON");
        curl_setopt($weather,CURLOPT_HEADER,false);

        $w36h=curl_exec($weather);
        curl_close($weather);
        return $w36h;
    }
     get_weather36h();

    ?>
    <div>
        <nav class="container" >
                <!-- Brand/logo -->
                <h1 class="navbar-brand" >小雞氣象局（｡･ө･｡）</h1>

                <!-- Links -->
                <ul class="navbar-nav">

                </ul>


            </nav>
            <div  class="select">
                <form action="" method="post">
                    <label for="country">縣市</label>
                    <select name="" id="country">

                        <option value="">1</option>
                    </select>
                </form>
            </div>
            

    </div>
   
</body>

</html>