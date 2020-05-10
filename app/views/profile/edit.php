<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/string.php"); 
require_once("app/functions/request.php"); 
require_once("app/functions/strip_output.php"); 

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

                    <form action="?id=<?=functions\request::index("GET","id")?>" method="post" id="catalogItemForm" enctype="multipart/form-data" autocomplete="off">
                      <div class="form-group">
                        <label>Заголовка*</label> 
                        <input type="text" class="form-control" name="title" id="title" value="<?=htmlentities($data['editdata']['title'])?>" />
                      </div> 

                      <div class="form-group">
                        <label>Тип продажи*</label> 
                        <section class="filter-select-item">
                          <input type="hidden" name="sale_price" id="sale_price" value="<?=$data['editdata']['sale_type']?>" />
                          <select title="filter" name="sale_price2" id="sale_price2" class="form-control" style="border: none; border-radius: 0px; color:#555555" disabled="disabled">
                            <?php foreach($data["salestype"] as $sale) : ?>
                            <option value="<?=$sale["idx"]?>"><?=$sale["title"]?></option> 
                            <?php endforeach; ?>
                          </select>

                          <script type="text/javascript">
                            $(document).ready(function(){
                              $("#sale_price2").val("<?=$data['editdata']['sale_type']?>");
                              // $("#sale_price").trigger("change");
                            });
                          </script>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label>Комната*</label> 
                        <section class="filter-select-item">
                          <select title="filter" name="rooms" id="rooms" class="form-control" style="border: none; border-radius: 0px;">
                            <?php foreach($data["rooms"] as $room) : ?>
                            <option value="<?=$room["idx"]?>" <?=($data["editdata"]["rooms"]==$room["idx"]) ? "selected='selected'" : ""?>><?=$room["title"]?></option> 
                            <?php endforeach; ?>
                          </select>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label>Тип недвижимости*</label> 
                        <section class="filter-select-item">
                          <select title="filter" name="type" id="type" class="form-control" style="border: none; border-radius: 0px;">
                            <?php foreach($data["type"] as $type) : ?>
                            <option value="<?=$type["idx"]?>" <?=($data["editdata"]["type"]==$type["idx"]) ? "selected='selected'" : ""?>><?=$type["title"]?></option> 
                            <?php endforeach; ?>
                          </select>
                        </section> 
                      </div>

                      <div class="form-group">
                        <label>
                          <?php 
                          if($data['editdata']['sale_type']==54){//day
                            echo "Суточная Цена на текущий месяц  (в долларах USD)";
                          }else if($data['editdata']['sale_type']==55){//month
                            echo "Месячная цена на текущий месяц  (в долларах USD)";
                          }else{
                            echo "Цена* - USD";
                          }
                          ?>
                        </label> 
                        <input type="text" class="form-control" name="price" id="price" value="<?=htmlentities($data["editdata"]["price"])?>" />
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
                            $editprice_explode = explode(",", $data["editdata"]["pricebymonth"]);
                            $editprice_list = array();
                            foreach ($editprice_explode as $v) {
                              $ex = explode("@@", $v);
                              if(isset($ex[0]) && isset($ex[1])){
                                $editprice_list[$ex[0]] = (int)$ex[1];
                              }
                            }

                            $monthArray = array("jan"=>"Январь", "feb"=>"Февраль", "mar"=>"Март", "apr"=>"Апрель", "may"=>"Май", "jun"=>"Июнь", "jul"=>"Июль", "aug"=>"Август", "sep"=>"Сентябрь", "oct"=>"Октябрь", "nov"=>"Ноябрь", "dec"=>"Декабрь");
                            foreach ($monthArray as $key => $value):
                              $priDay = (isset($editprice_list['day'.$key])) ? $editprice_list['day'.$key] : '';
                              $priMonth = (isset($editprice_list['month'.$key])) ? $editprice_list['month'.$key] : '';
                            ?>
                            <tr>
                              <td><?=$value?></td>
                              <td class="daypriceColumn">
                                <div class="form-group">
                                  <input type="text" class="form-control pricebymonth" name="day<?=$key?>" id="day<?=$key?>" value="<?=$priDay?>" />
                                </div>
                              </td>
                              <td class="monthpriceColumn" style="display:none">
                                <div class="form-group">
                                  <input type="text" class="form-control pricebymonth" name="month<?=$key?>" id="month<?=$key?>" value="<?=$priMonth?>" />
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
                            $aditionalinfo_explode = explode(",", $data['editdata']['additional_data']);
                            $additionalinfo_input = array();
                            foreach ($aditionalinfo_explode as $v) {
                              $ex = explode("=", $v);
                              if(isset($ex[0]) && isset($ex[1])){
                                $additionalinfo_input[$ex[0]] = $ex[1];
                              }
                            }

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
                                      <option value="+" <?=(isset($additionalinfo_input["input[".$value["idx"]."]"]) && $additionalinfo_input["input[".$value["idx"]."]"]=="+") ? "selected='selected'" : ""?>>+</option> 
                                      <option value="-" <?=(isset($additionalinfo_input["input[".$value["idx"]."]"]) && $additionalinfo_input["input[".$value["idx"]."]"]=="-") ? "selected='selected'" : ""?>>-</option> 
                                    </select>
                                  </section> 
                                  <?php endif; ?>

                                  <?php if($description!="yesno"):?>
                                  <input type="text" class="form-control additionalinfos" name="input[<?=$value["idx"]?>]" value="<?=(isset($additionalinfo_input["input[".$value["idx"]."]"])) ? $additionalinfo_input["input[".$value["idx"]."]"] : ''?>" />
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
                        <textarea rows="4" class="form-control" name="description" id="description" style="resize: none;"><?=(isset($data["editdata"]["description"])) ? strip_tags($data["editdata"]["description"]) : ""?></textarea>
                      </div>

                      <div class="form-group">
                        <label>Адрес*</label> 
                        <input type="text" class="form-control" name="address" id="address" value="<?=(isset($data["editdata"]["address"])) ? strip_tags($data["editdata"]["address"]) : ""?>" />
                      </div>

                      <div class="form-group">
                        <label>Карта* ( Перетащите маркер карты )</label> 
                        <input type="hidden" name="mapCords" class="mapCords" id="mapCords" value="<?=(isset($data["editdata"]["location"])) ? strip_tags($data["editdata"]["location"]) : ""?>" />
                        <div id="map" style="width:100%; height: 350px;"></div>
                      </div>

                      <div class="form-group uploadfilesbox">
                        <label style="width:100%;">Картины*</label> 
                        <div class="imageList" style="width: 100%; clear:both">

                      <?php 
                      if(count($data['photos'])){
                        foreach ($data['photos'] as $v):
                        ?>
                        <div class="image imgList<?=$v['id']?>" style="float: left; margin: 0 10px 10px 0; position:relative">
                          <img src="<?=Config::WEBSITE.$_SESSION["LANG"]?>/image/loadimage?f=<?=base64_encode(Config::WEBSITE_.$v["path"])?>&w=<?=base64_encode("100")?>&h=<?=base64_encode("100")?>" width="100" height="100" alt="" />
                          <div class="closefilephoto insertedimageremove" data-imageid="<?=$v['id']?>" data-parent="<?=(int)functions\request::index("GET","id")?>" data-input="box<?=$v['id']?>" data-image="imgList<?=$v['id']?>">X</div>
                        </div>
                        <?php 
                        endforeach;
                      }
                      ?>


                        </div>
                        <div style="clear:both"></div>

                        <a href="javascript:void(0)" class="text-primary btn btn-success add-photki">Добавить изображение</a>
                        <div class="filesBox box1" style="margin:10px 0; position:relative; visibility:hidden; position:absolute;">
                              <input type="file" class="form-control catalogitemfile" accept="image/*" name="file[]" class="files" value="" data-oldid="box1" />
                        </div>  
                        <?php 
                        if(count($data['photos'])){
                          foreach ($data['photos'] as $v):
                          ?>  
                            <div class="filesBox box<?=$v['id']?>" style="margin:10px 0; position:relative; visibility:hidden; position:absolute;">
                              <input type="file" class="form-control catalogitemfile" accept="image/*" name="file[]" class="files" value="<?=$v['path']?>" data-oldid="box<?=$v['id']?>" />
                            </div> 
                          <?php 
                          endforeach;
                        }
                        ?>               
                        
                       
                        
                      </div>
                      <hr />

                      <a href="/ru/profile" class="text-primary btn btn-success"><i class="fa fa-long-arrow-left"></i>&emsp;Назад</a>
                      <a href="javascript:void(0)" class="text-primary btn btn-success edit-new-item">Обновить</a>
                      

                    </form>
                  </section>
              

          </section>
          </section>

        </section>
      </section>
    </section>
  </main>

<script type="text/javascript">
$(document).on("change", "#sale_price", function(){
  var val = $(this).val();
  if(val==54){
    $(".monthdaypriceBox").show();
    $(".daypriceColumn").show();
    $(".monthpriceColumn").hide();
  }else if(val==55){
    $(".monthdaypriceBox").show();
    $(".daypriceColumn").hide();
    $(".monthpriceColumn").show();
  }else{
     $(".monthdaypriceBox").hide();
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
  <?php 
  $location = explode(":", $data["editdata"]["location"]);
  $lat = (isset($location[0])) ? $location[0] : '41.6339313';
  $long = (isset($location[1])) ? $location[1] : '41.5889358';
  ?>
  var myLatlng = new google.maps.LatLng(<?=$lat?>,<?=$long?>);
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: myLatlng
  });

  marker = new google.maps.Marker({
    position: myLatlng,
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrie5OzMEhuNQfobSb5ex4adufewpoDKU&amp;callback=initMap"></script>

<?=$data['footer']?>