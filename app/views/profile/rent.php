<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/string.php"); 
require_once("app/functions/request.php"); 
require_once("app/functions/strip_output.php"); 
require_once("app/functions/request.php");

$l = new functions\l(); 
$string = new functions\string(); 
echo $data['headerModule']; 
echo $data['headertop']; 
?>

<main>
    <?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?=$actual_link?>" target="_blank" class="facebook-share"><i class="fa fa-facebook-square fa-2x"></i></a>
    <section class="container">
      <section class="row containerRow">
        <section class="col-md-4 left showonmobileleft">
          <section class="list-group">
            <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
              <h4 class="usersh4">Пользователь: <?=$data["user"]["firstname"]?></h4>
              <ul class="profile-nav">
                <li>
                  <a href="/<?=$_SESSION["LANG"]?>/profile?view=home">
                    Мои заявления 
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="/<?=$_SESSION["LANG"]?>/profile?view=edit">Профиль</a>
                </li>
                <li>
                  <a href="/<?=$_SESSION["LANG"]?>/profile?view=update-password">Обновить пароль</a>
                </li>
                <li><a href="/<?=$_SESSION["LANG"]?>/login">Выйти</a></li>
              </ul>
            </section>
             
          </section>
        </section>
        <section class="col-md-8 right">
          <!-- -->
          <section class="flat-list">
            <section class="list-group">                             

                  <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
                  
                    <section class="output-message" style="background-color: rgba(20,202,33,.75); <?=(!empty($data["postMessage"])) ? "display:block" : "display:none"?>"><?=$data["postMessage"]?></section>

                    <form action="?" method="post" id="catalogItemForm" enctype="multipart/form-data" autocomplete="off">
                      <div class="form-group">
                        <label>Заголовка*</label> 
                        <input type="text" class="form-control" name="title" id="title" value="<?=(functions\request::index("POST", "title")) ? htmlentities(functions\request::index("POST", "title")) : ""?>" />
                      </div> 

                      <div class="form-group">
                        <label>Тип продажи*</label> 
                        <section class="filter-select-item">
                          <select title="filter" name="sale_price" id="sale_price" class="form-control" style="border: none; border-radius: 0px;">
                            <?php foreach($data["salestype"] as $sale) : ?>
                            <option value="<?=$sale["idx"]?>" <?=(functions\request::index("POST", "sale_price") && functions\request::index("POST", "sale_price")==$sale["idx"]) ? "selected='selected'" : ""?>><?=$sale["title"]?></option> 
                            <?php endforeach; ?>
                          </select>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label>Комната*</label> 
                        <section class="filter-select-item">
                          <select title="filter" name="rooms" id="rooms" class="form-control" style="border: none; border-radius: 0px;">
                            <?php foreach($data["rooms"] as $room) : ?>
                            <option value="<?=$room["idx"]?>" <?=(functions\request::index("POST", "rooms") && functions\request::index("POST", "rooms")==$room["idx"]) ? "selected='selected'" : ""?>><?=$room["title"]?></option> 
                            <?php endforeach; ?>
                          </select>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label>Тип недвижимости*</label> 
                        <section class="filter-select-item">
                          <select title="filter" name="type" id="type" class="form-control" style="border: none; border-radius: 0px;">
                            <?php foreach($data["type"] as $type) : ?>
                            <option value="<?=$type["idx"]?>" <?=(functions\request::index("POST", "type") && functions\request::index("POST", "type")==$type["idx"]) ? "selected='selected'" : ""?>><?=$type["title"]?></option> 
                            <?php endforeach; ?>
                          </select>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label id="mainprice_label">Цена* - USD</label> 
                        <input type="text" class="form-control" name="price" id="price" value="<?=(functions\request::index("POST", "price")) ? htmlentities(functions\request::index("POST", "price")) : ""?>" />
                      </div> 

                      <div class="form-group monthdaypriceBox">
                        <!-- <label>Стоимость в месяц</label>  -->
                        <table style="margin:0; width:100%;" class="monthpricetable">
                          <thead>
                            <th>Месяц</th>
                            <th class="daypriceColumn">В соответствующем месяце Укажите суточную цену, (USD)</th>
                            <th class="monthpriceColumn" style="display:none">В соответствующем месяце Укажите месячную цену, (USD)</th>
                          </thead>
                          <tbody>
                            <?php 
                            $monthArray = array("jan"=>"Январь", "feb"=>"Февраль", "mar"=>"Март", "apr"=>"Апрель", "may"=>"Май", "jun"=>"Июнь", "jul"=>"Июль", "aug"=>"Август", "sep"=>"Сентябрь", "oct"=>"Октябрь", "nov"=>"Ноябрь", "dec"=>"Декабрь");
                            foreach ($monthArray as $key => $value):
                            ?>
                            <tr>
                              <td><?=$value?></td>
                              <td class="daypriceColumn">
                                <div class="form-group">
                                  <input type="text" class="form-control pricebymonth" name="day<?=$key?>" id="day<?=$key?>" value="<?=(functions\request::index("POST", "day{$key}")) ? htmlentities(functions\request::index("POST", "day{$key}")) : ""?>" />
                                </div>
                              </td>
                              <td class="monthpriceColumn" style="display:none">
                                <div class="form-group">
                                  <input type="text" class="form-control pricebymonth" name="month<?=$key?>" id="month<?=$key?>" value="<?=(functions\request::index("POST", "month{$key}")) ? htmlentities(functions\request::index("POST", "month{$key}")) : ""?>" />
                                </div>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div> 

          
                      <div class="form-group">
                        <label>Дополнительная информация</label> 
                        <table style="margin:0; width:100%;" class="monthpricetable">
                          <tbody>
                            <?php 
                            $i = 0;
                            foreach ($data["additionalinfo"] as $value):
                            ?>
                            <tr>
                              <td><?=$value["title"]?></td>
                              <td>
                                <div class="form-group">
                                  <?php 
                                  $description = strip_tags($value["description"]);
                                  if($description=="yesno"):?>
                                  <section class="filter-select-item">
                                    <select title="filter" name="input[<?=$value["idx"]?>]" class="form-control additionalinfos" style="border: none; border-radius: 0px;">
                                      <option value="+" <?=(isset($_POST["input"][$value["idx"]]) && $_POST["input"][$value["idx"]]=="+") ? "selected='selected'" : ""?>>+</option> 
                                      <option value="-" <?=(isset($_POST["input"][$value["idx"]]) && $_POST["input"][$value["idx"]]=="-") ? "selected='selected'" : ""?>>-</option> 
                                    </select>
                                  </section> 
                                  <?php endif; ?>

                                  <?php if($description!="yesno"):?>
                                  <input type="text" class="form-control additionalinfos" name="input[<?=$value["idx"]?>]" value="<?=(isset($_POST["input"][$value["idx"]])) ? htmlentities($_POST["input"][$value["idx"]]) : ""?>" />
                                  <?php endif; ?>
                                </div>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div> 


                      <div class="form-group">
                        <label>Описание*</label> 
                        <textarea rows="4" class="form-control" name="description" id="description" style="resize: none;"><?=(functions\request::index("POST", "description")) ? htmlentities(functions\request::index("POST", "description")) : ""?></textarea>
                      </div>

                      <div class="form-group">
                        <label>Адрес*</label> 
                        <input type="text" class="form-control" name="address" id="address" value="<?=(functions\request::index("POST", "address")) ? htmlentities(functions\request::index("POST", "address")) : ""?>" />
                      </div>

                      <div class="form-group">
                        <label>Карта* ( Перетащите маркер карты )</label> 
                        <input type="hidden" name="mapCords" class="mapCords" id="mapCords" value="<?=(functions\request::index("POST", "mapCords")) ? htmlentities(functions\request::index("POST", "mapCords")) : ""?>" />
                        <div id="map" style="width:100%; height: 350px;"></div>
                      </div>

                      <div class="form-group uploadfilesbox">
                        <label style="width:100%;">Картины*</label> 

                        <div class="imageList" style="width: 100%; clear:both"></div>
                        <div style="clear:both"></div>

                        <a href="javascript:void(0)" class="text-primary btn btn-success add-photki">Добавить изображение</a>
                        <div class="filesBox box1" style="margin:10px 0; position:relative; visibility:hidden; position:absolute;">
                          <input type="file" class="form-control catalogitemfile" accept="image/*" name="file[]" class="files" value="" data-oldid="box1" />
                        </div>
                       
                        
                      </div>
                      <hr />

                      <a href="/ru/profile" class="text-primary btn btn-success"><i class="fa fa-long-arrow-left"></i>&emsp;Назад</a>
                      <a href="#" class="text-primary btn btn-success add-new-item">Добавить</a>
                      

                    </form>
                  </section>
              

          </section>
          </section>

        </section>
      </section>
    </section>
  </main>

<script>
$("#mainprice_label").html("Суточная Цена на текущий месяц  (в долларах USD)");

$(document).on("change", "#sale_price", function(){
  var val = $(this).val();
  if(val==54){// day
    $(".monthdaypriceBox").show();
    $(".daypriceColumn").show();
    $(".monthpriceColumn").hide();

    $("#mainprice_label").html("Суточная Цена на текущий месяц  (в долларах USD)");
  }else if(val==55){// month
    $(".monthdaypriceBox").show();
    $(".daypriceColumn").hide();
    $(".monthpriceColumn").show();
    $("#mainprice_label").html("Месячная цена на текущий месяц  (в долларах USD)");
  }else{// other
     $(".monthdaypriceBox").hide();
     $("#mainprice_label").html("Цена* - USD");
  }
});

$(document).on("click",".add-photki", function(e){
  $(".catalogitemfile").click();
});
$(document).on("change",".catalogitemfile", function(e){
  $(".catalogitemfile").removeClass("catalogitemfile");
  var oldid = $(this).attr("data-oldid");
  var len = Math.random().toString(36).substr(2);
 
  var file = URL.createObjectURL(e.target.files[0]);

  if($(this).val()!=""){

    var html = "<div class=\"filesBox box"+len+"\" style=\"margin:10px 0; position:relative; visibility:hidden; position:absolute;\">";
    html += "<input type=\"file\" class=\"form-control catalogitemfile\" accept=\"image/*\" name=\"file[]\" class=\"files\" value=\"\" data-oldid=\""+len+"\" />";
    html += "</div>";
    $(".uploadfilesbox").append(html);

    var imageList = "<div class=\"image imageList"+len+"\" style=\"float: left; margin: 0 10px 10px 0; position:relative\">";
    imageList += "<img src=\""+file+"\" width=\"100\" height=\"100\" alt=\"\" />";
    imageList += "<div class=\"closefilephoto\" data-input=\""+oldid+"\" data-image=\"imageList"+len+"\">X</div>";
    imageList += "</div>";
    
    $(".imageList").append(imageList);
  }
});

$(document).on("click",".closefilephoto", function(e){
  var input = $(this).attr("data-input");
  var image = $(this).attr("data-image");
  $("."+input).remove(); 
  $("."+image).remove(); 
});

var marker;
var map;

function initMap() {
  var myLatlng = new google.maps.LatLng(41.63495769608863,41.630907006664984);
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: myLatlng
  });

  marker = new google.maps.Marker({
    position: { lat: 41.63495769608863, lng: 41.630907006664984 },
    map: map,
    draggable:true,
    icon:'/public/img/marker.png',
    title: ''
  });

  $("#mapCords").val("41.63495769608863:41.630907006664984");

  google.maps.event.addListener(marker, 'dragend', function(e) { 
    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();

    $("#mapCords").val(lat + ":" + lng);
  });


  new google.maps.event.addListener(map, 'click', function(event) {
        if(typeof marker !== "undefined"){
          marker.setMap(null);
        }
        var la = event.latLng.lat();
        var ln = event.latLng.lng();
        $("#mapCords").val(la + ":" + ln);

        marker = new google.maps.Marker({
          position: { lat: la, lng: ln },
          map: map,
          draggable:true,
          icon:'/public/img/marker.png',
          title: ''
        });

        google.maps.event.addListener(marker, 'dragend', function(e) { 
          var lat = marker.getPosition().lat();
          var lng = marker.getPosition().lng();

          $("#mapCords").val(lat + ":" + lng);
        });

  });

  

  

}      
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=Config::GOOGLE_MAP_KEY?>&amp;callback=initMap"></script>

<?=$data['footer']?>