# RD1_Assignment
CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B

天氣背景圖為Pixabay https://pixabay.com/zh/photos/sky-stars-constellations-astronomy-828648/ 下載

預計
資料庫
１．存放縣市  鄉鎮
２．縣市

# 0901 抓資料  使用curl
取得３６Ｈ資料以及縣市

# 0902 抓縣市特色圖片
縣市特色圖片源自觀光局：https://www.taiwan.net.tw/
台中市谷關
台北市陽明山
台南市赤崁樓
宜蘭縣龜山
花蓮縣七星
金門縣莒光樓
南投縣衫林溪
屏東縣小琉球
苗栗縣龍騰
桃園市大溪
高雄市月世界
基隆市和平島
連江縣八八坑道
雲林縣北港
新北市九份
新竹市青草湖
新竹縣北浦
嘉義市阿里山
嘉義縣布袋
彰化縣田尾公路
臺東縣綠島
澎湖縣跨海大橋

# 0902 抓氣象圖片  切割
氣象圖標圖片源自於Pngtree：https://zh.pngtree.com/so/%E5%A4%A9%E6%B0%A3%E7%B4%A0%E6%9D%90

# 0902 將縣市 圖片 放到資料庫

# 0902




### 題目

製作一個個人氣象網站，並且實作以下功能:
縣市選擇:可自行選擇要查看的縣市               --\  使用   一般天氣預報-今明 36 小時天氣預報  時間限制為當前時間
顯示縣市當前天氣狀況                        --/  
顯示縣市未來2天、1週天氣預報                     －－> 使用  鄉鎮天氣預報-宜蘭縣未來2天 、 一週天氣預報

台灣未來兩天，locationName放縣市名稱=>https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&elementName=WeatherDescription&locationName="當前縣市"

使用元素
晴。
降雨機率 20%。
溫度攝氏32度。
易中暑。
西南風 平均風速1-2級(每秒2公尺)。
相對濕度72%



台灣未來一週，=>https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-CD094466-F0F5-46D5-B4CE-B55F5026618B&locationName="當前縣市"&elementName=WeatherDescription

顯示縣市各觀測站過去1小時、24小時累積雨量數據       －－> 使用    
切換縣市時，顯示該縣市的特色圖片                －－>去觀光局找
上述各式氣象資料，請一併儲存於資料庫
介面排版與所需素材請自由發揮



縣市資料和現在天氣狀況先抓出來

資料為每筆每三小時一次
resource_id  資源碼



* Wx 天氣現象  天氣狀況
value對應天氣
1  晴                sunday.png
2  
3
4  多雲              cloudsday.png
5
6
7  陰                cloudday.png
8  短暫陣雨           rainday.png
9
10
11
12
13
14
15  短暫陣雨或雷雨      thunderrainday.png

* MaxT 最高溫度


* MinT 最低溫度
* CI 舒適度
舒適度指數
* RH 相對濕度


* PoP 降雨機率 

* Wind 未來兩天及兩週沒有