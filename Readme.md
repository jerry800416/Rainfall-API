## Rainfall API 
>[time=Thu, Mar 12, 2020 4:05 PM]
### 用途
查詢資料庫內對應塔號或支持物號的月雨量、日雨量、時雨量、以及三小時雨量。

### 用法
網址後面設定變數值：分別有TowerID、SupporterID、target_time、type。
#### TowerID / SupporterID (必填)
直接打上 TowerID=["想查詢的塔號"] 或 SupporterID=["想查詢的支持物號"]
>如果要查35號塔：TowerID=35
>
>如果要查46404支持物編號：SupporterID=46404
> 
><font color = red> 注意！如果兩者皆有選填，將以支持物編號優先查詢結果</font>

#### target_time(必填)
時間有兩種查法：
1. 一個時間點：
 顯示出最接近那個時間點的相關資料，精確到小時。
 
    >若要查詢2019年11月3號15時的雨量：target_time=2019110315

    這將會顯示最接近2019年11月3號15時(前)的相對應雨量資料(一筆)。==此種查法不會顯示三小時雨量的資料==。

2. 一段時間：
    顯示出這段時間內的資料，同樣精確到小時。
    (要注意的是，若時間區間未跨越月或日，將不會有月雨量資料以及日雨量資料)
    >若要查詢
    >2019年11月3號15時到2019年12月1號14時的雨量：
    >target_time=2019110315-2019120114

    這將會顯示最接近2019年11月3號15時(包含)到2019年12月1號14時的相對應雨量資料。
#### type(選填)
若未填此項，預設將顯示出全部種類的資料。
- 若要只顯示月資料
     >type=month
- 若要只顯示天資料
     >type=day
- 若要只顯示小時資料
     >type=hour
- 若要只顯示三小時資料
     >type=three_hour
