<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/string.php"); 
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
        <?=$data["leftnav"]?>

        <section class="col-md-8 right">
          
          <section class="list-group">
            <section class="list-group-item mobile-list-group-item">
              <button class="btn btn-success btn-block showFilter">
                <h4>Показать фильтры</h4>
              </button>
            </section>


            <section class="list-group-item desktop-list-group-item">
              <section class="row">
                <section class="col-sm-3 filter-select-item">
                  <select title="filter" class="form-control" id="cities">
                    <option value="">Город</option> 
                    <?php 
                    foreach($data["cities"] as $city) : 
                      $selected = (isset($_SESSION["query"]["cities"]) && $_SESSION["query"]["cities"]==$city["idx"]) ? 'selected="selected"' : '';    
                      $selected = ($selected=="" && $city["idx"]==$data["selected_city"]) ? 'selected="selected"' : $selected;                  
                    ?>                    
                    <option value="<?=$city["idx"]?>" <?=$selected?>><?=$city["title"]?></option> 
                    <?php endforeach; ?>
                  </select>
                </section>
                <section class="col-sm-3 filter-select-item">
                  <select title="filter" class="form-control" id="fsale_type">
                    <option value="">Тип продажи</option> 
                    <?php 
                    foreach($data["salestype"] as $sale) : 
                    $selected = (isset($_SESSION["query"]["sale_type"]) && $_SESSION["query"]["sale_type"]==$sale["idx"]) ? 'selected="selected"' : '';
                    $selected = ($selected=="" && $sale["idx"]==54) ? 'selected="selected"' : $selected;
                    ?>                    
                    <option value="<?=$sale["idx"]?>" <?=$selected?>><?=$sale["title"]?></option> 
                    <?php endforeach; ?>
                  </select>
                </section> 
                <section class="col-sm-3 filter-select-item">
                  <select title="filter" class="form-control" id="ftype">
                    <option value="">Тип недвижимости</option> 
                    <?php 
                    foreach($data["type"] as $type) : 
                    $selected = (isset($_SESSION["query"]["type"]) && $_SESSION["query"]["type"]==$type["idx"]) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?=$type["idx"]?>" <?=$selected?>><?=$type["title"]?></option> 
                    <?php endforeach; ?>
                  </select>
                </section>
                <section class="col-sm-3 filter-select-item">
                  <select title="filter" class="form-control" id="frooms">
                    <option value="">Комнаты</option> 
                    <?php 
                    foreach($data["rooms"] as $rooms) : 
                    $selected = (isset($_SESSION["query"]["rooms"]) && $_SESSION["query"]["rooms"]==$rooms["idx"]) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?=$rooms["idx"]?>" <?=$selected?>><?=$rooms["title"]?></option> 
                    <?php endforeach; ?>
                  </select>
                </section> 
                
              </section> 
              <br> 
              <section class="row">
                <section class="col-sm-2 filter-select-item">
                  <input type="number" min="5" title="цена от" id="fprice_from" placeholder="цена от" class="form-control" value="<?=(isset($_SESSION["query"]["price_from"]) ? $_SESSION["query"]["price_from"] : '')?>">
                </section> 
                <section class="col-sm-2 filter-select-item">
                  <input type="number" min="5" title="цена до" id="fprice_to" placeholder="цена до" class="form-control" value="<?=(isset($_SESSION["query"]["price_to"]) ? $_SESSION["query"]["price_to"] : '')?>">
                </section> 
                <br class="hidden-md hidden-lg hidden-sm"> 
                <section class="col-sm-4 filter-select-item">
                  <section class="row">
                    <section class="col-sm-6">
                      <button class="btn btn-success pull-left searchButton">
                        Найти <i class="fa fa-search"></i>
                      </button>
                    </section>
                    <section class="col-sm-6">
                      <?php 
                      if(isset($_SESSION["query"]["orderby"]) && $_SESSION["query"]["orderby"]=="desc"){
                        $or = "desc";
                      }else if(isset($_SESSION["query"]["orderby"]) && $_SESSION["query"]["orderby"]=="asc"){
                        $or = "asc";
                      }else{
                        $or = "asc";
                      }
                      ?>

                      <input type="hidden" name="itemPerPage" id="itemPerPage" value="<?=$data["itemPerPage"]?>" />
                      <input type="hidden" name="forderby" id="forderby" value="<?=$or?>" />
                      <input type="hidden" name="pn" id="pn" value="<?=(isset($_SESSION["query"]["pn"]) ? (int)$_SESSION["query"]["pn"]+1 : 2)?>" />
                      <button class="btn btn-success pull-right change-order-list" style="visibility: hidden;">
                        Сортировка
                        <i class="fa fa-sort-numeric-<?=$or?>"></i>
                      </button>
                    </section>
                  </section>
                </section>

                <section class="col-sm-4 filter-select-item">
                  <section class="row">
                    <section class="col-sm-6">&nbsp;</section>
                    <section class="col-sm-6">
                      <button class="btn btn-success pull-right searchMap">
                        Поиск по карте <i class="fa fa-map-marker"></i>
                      </button>
                    </section>
                  </section>
                </section>

              </section>
            </section>


          </section> 


          <section class="flat-list">
            <section class="list-group">

              <div style="padding: 10px; background-color: rgba(18,115,185,.7)">
                <button onclick="window.history.back()" class="text-primary btn btn-success" style="float:left">Назад</button>
                <div style="display: inline-block; color:#ffffff; float:right; margin-left: 80px; margin-top: -38px;">Если хотите посмотреть цену конкретной квартиры и ее фотографии,  и.т.д, нажмите палец или маус на зеленую отметку, который видно на карте.</div>
                <div style="clear:both"></div>
              </div>

              <div id="results">
                <div id="map" style="width: 100%; height: 450px; text-align: center;">
                  <img src="/public/img/loading.gif" alt="" width="50" height="50" style="margin: 175px auto" />
                </div>
              </div>

            </section>
          </section>
              





        </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>


<script type="text/javascript">
function closeAllInfoWindows(infoWindows2) {
  for (var i=0;i<infoWindows2.length;i++) {
    if(typeof infoWindows2[i] !== "undefined"){
      infoWindows2[i].close();
    }
  }
}

var map;
function initMap() {
  var mapOptions = {
        zoom: 16
  };
  map = new google.maps.Map(document.getElementById('map'), mapOptions);

  var locations = [
    <?php 
    foreach ($data["products"] as $item) :
      $additionaldata = explode(",", $item["additional_data"]);
      $ploshad_explode = explode("input[35]=", @$additionaldata[0]);
      // $etaj_explode = explode("input[36]=", @$additionaldata[1]);

      $realprice = 0;
      $curname = "";
      if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="gel"){
        $realprice = (int)($item["price"]*$_SESSION["_USD_"]);
        $curname = "GEL";
      }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="usd"){
        $realprice = (int)$item["price"];
        $curname = "USD";
      }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="rub"){
        $realprice = (int)((int)($item["price"]*$_SESSION["_USD_"])/$_SESSION["_RUB_"]);
        $curname = "RUB";
      }

      $m2priceString = "";
      if($item["sale_type"]=="Продажа" && (int)$ploshad_explode[1]>0){
        $m2price = round($realprice / (int)$ploshad_explode[1]);
        $m2priceString = sprintf("<br /><em style=\"font-size:12px; font-style:normal;\">%s %s / м<sup>2</sup></em>", $m2price, $curname);
      }


      $locations = explode(":", $item["location"]);
      if(isset($locations[0]) && isset($locations[1])):
        echo "[";
        echo sprintf("'%s',", htmlentities($item["title"]));
        echo sprintf("%s,", $locations[0]);
        echo sprintf("%s,", $locations[1]);
        echo "'/public/img/marker.png',";

        $m2 = "0 м<sup>2</sup>";
        if(preg_match_all("/input\[35\]\=(\d+)/", $item["additional_data"], $ma)){
          $m2 = $ma[1][0]." м<sup>2</sup>";
        }

        $spalni = 0;
        if(preg_match("/input\[37\]\=(\d+)/", $item["additional_data"], $spalni)){
          $spalni = (int)$spalni[1];
        }

        $etaj = 0;
        if(preg_match("/input\[36\]\=(\d+)/", $item["additional_data"], $etaj)){
          $etaj = print_r($etaj[1], true);
        }

        $totalfloors = 0;
        if(preg_match("/input\[93\]\=(\d+)/", $item["additional_data"], $totalfloors)){
          $totalfloors = print_r($totalfloors[1], true);
        }

        $centa = ($item["sale_type"]=="Посуточная аренда") ? "Цена за сутки" : "Цена";
                
        echo sprintf("'<div class=\"infowindow\" id=\"info%d\"><h5><a href=\"%s\">%s</a></h5><div class=\"imageBox g-container%s\" data-loading=\"false\"><div class=\"g-infox\"><span class=\"g-activex\">1</span> - <span class=\"g-maxx\">%s</span></div><div class=\"g-leftar g-arrow\" data-idx=\"%s\" data-active=\"1\" data-max=\"%s\"><i class=\"fa fa-angle-left\"></i></div><a href=\"%s\" class=\"g-image-sliderx\" style=\"display:block\" data-image=\"%s\"></a><div class=\"g-rightar g-arrow\" data-idx=\"%s\" data-active=\"1\" data-max=\"%s\"><i class=\"fa fa-angle-right\"></i></div></div><div class=\"textBox\"><h6 style=\"font-size:15px;\"><strong>%s&nbsp;ID %s</strong></h6><a href=\"%s\"><font>%s</font><font>%s Спальни</font><font>%s\/%s Этаж</font><font class=\"addr\">Адрес: %s</font></a><span class=\"m2\">%s</span></div></div>',", 
          $item["idx"],
          "/ru/view/".urlencode(strip_tags($item["title"]))."/?id=".(int)$item["idx"],
          $centa.": <span>".$realprice." ".$curname.$m2priceString."</span>",
          $item["idx"],
          $item["photoCount"],
          $item["idx"],
          $item["photoCount"],
          "/ru/view/".urlencode(strip_tags($item["title"]))."/?id=".(int)$item["idx"],
          Config::WEBSITE.$_SESSION["LANG"]."/image/loadimage?f=".base64_encode(Config::WEBSITE_.$item["photo"])."&w=".base64_encode("180")."&h=".base64_encode("180"),
          $item["idx"],
          $item["photoCount"],
          $item["title"],
          $item["orderid"],
          "/ru/view/".urlencode(strip_tags($item["title"]))."/?id=".(int)$item["idx"],
          strip_tags($item["rooms"]),
          $spalni,
          $etaj,
          (int)$totalfloors,
          $item["address"],
          $m2
        );

        echo sprintf("'info%s'", $item["idx"]);

        echo "],";
      endif;
    endforeach;
    ?>
  ];
  
  var bounds = new google.maps.LatLngBounds();
  

  var marker = new Array();
  var contentString = new Array();
  var infowindow = new Array();
  for (var i = 0; i < locations.length; i++) {  
    
    contentString.push(locations[i][4]);

    infowindow.push(new google.maps.InfoWindow({
      content: contentString[i],
      id:locations[i][5]
    }));

    marker.push(new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map: map,
      animation: google.maps.Animation.DROP,
      title: locations[i][0],
      icon: locations[i][3]
    }));

    google.maps.event.addListener(marker[i],'click', (function(marker,infowindow, allinfo){ 
      return function() {
          closeAllInfoWindows(allinfo);
          infowindow.open(map,marker);

          console.log(infowindow.id);

          setTimeout(function(){
            let swiper = new Swipe(document.getElementsByClassName("g-image-sliderx")[0]);
            swiper.onLeft(function() { 
              // console.log(this.element.parentNode.childNodes[3]);
              this.element.parentNode.childNodes[3].click();
            });

            swiper.onRight(function() { 
              this.element.parentNode.childNodes[1].click();
            });
            swiper.run();

            let img = $("#"+infowindow.id+" .g-image-sliderx").attr("data-image");
            let imgCreate = "<img src=\""+img+"\" alt=\"\" />";
            $("#"+infowindow.id+" .g-image-sliderx").html(imgCreate);
          }, 1000);
      };
    })(marker[i],infowindow[i],infowindow)); 

    bounds.extend(marker[i].position);
  }
  
  map.fitBounds(bounds);

  google.maps.event.addListenerOnce(map, 'idle', function() {
    if (locations.length == 1) {
      map.setZoom(16);
    }
  });
}


</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=Config::GOOGLE_MAP_KEY?>"></script>
<script>
  (function(){
    setTimeout(function(){
      initMap();
    }, 500);
  })();
</script>

<?=$data['footer']?>