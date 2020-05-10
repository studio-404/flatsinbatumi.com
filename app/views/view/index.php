<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
$l = new functions\l(); 
echo $data['headerModule']; 
echo $data['headertop']; 

/*
** UPDATE VIEWS
*/
$db_views_update = new Database("products", array(
  "method"=>"update_views",
  "idx"=>$data["productGetter"]["idx"]
));
$db_views_update->getter();
?>

<main>
    <?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?=$actual_link?>" target="_blank" class="facebook-share"><i class="fa fa-facebook-square fa-2x"></i></a>
    <section class="container">
      <section class="row containerRow">
        <?=$data["leftnav"]?>
        
        <section class="col-md-8 right" style="color: white">

          <section class="list-group">
            <section class="list-group-item">
              
              <section class="flat-price" <?=($data["productGetter"]["showwebsite"]!=2) ? 'style="background-color:red; height:auto;"' : 'style="height:auto;'?>>
                <i class="fa fa-chevron-left"></i> 

                <?php 
                $realprice = 0;
                $curname = "";
                if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="gel"){
                  $realprice = (int)($data["productGetter"]["price"]*$_SESSION["_USD_"]);
                  $curname = "GEL";
                }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="usd"){
                  $realprice = (int)$data["productGetter"]["price"];
                  $curname = "USD";
                }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="rub"){
                  $realprice = (int)((int)($data["productGetter"]["price"]*$_SESSION["_USD_"])/$_SESSION["_RUB_"]);
                  $curname = "RUB";
                }
                ?>

                <span><?=$realprice?></span> <?=$curname?>
                <?php 
                $additionaldata = explode(",", $data["productGetter"]["additional_data"]);
                $ploshad_explode = explode("input[35]=", @$additionaldata[0]);
                // echo $data["productGetter"]["sale_type"];
                if($data["productGetter"]["sale_type"]==57 && (int)$ploshad_explode[1]>0){
                  $m2price = round($realprice / (int)$ploshad_explode[1]);
                  ?>
                  <br /><span style="font-size: 12px;"><?=$m2price?> <?=$curname?> м<sup>2</sup></span>
                  <?php
                }
                ?>
              </section>

              <section class="row">
                <section class="col-md-4">

                  <section class="row">
                    <section class="col-md-12 text-center">
                      <a href="javascript:void(0)" onclick="$('.firstChildImageGallery').click()" style="display: inline;">
                        <img alt="" src="<?=Config::WEBSITE.$_SESSION["LANG"]?>/image/loadimage?f=<?=base64_encode(Config::WEBSITE_.$data["photos"][0]["path"])?>&w=<?=base64_encode("220")?>&h=<?=base64_encode("220")?>" class="fa loading img-responsive img-thumbnail" />
                      </a>
                    </section>
                  </section>

                  <hr style="margin: 20px 5px;" />

                  <section style="margin:0 5px">
                    <section><?=$data["productGetter"]["sale_type_title"]?></section>
                    <section><?=$data["productGetter"]["rooms_title"]?></section>
                    <section><?=$data["productGetter"]["type_title"]?></section>
                  
                    <hr />
                    <?php  
                    $newMaches = array();
                    if(
                      preg_match_all(
                        "/input\[\d+\]=[a-z]+|input\[\d+\]=[0-9]+|input\[\d+\]=\+|input\[\d+\]=\-/", 
                        $data["productGetter"]["additional_data"], $matches
                      )
                    ){
                      if(isset($matches[0]))
                      {
                        foreach ($matches[0] as $v) {
                          if(
                            preg_match(
                              "/input\[(\d+)\]/", $v, $m
                            ) &&
                            preg_match(
                              "/input\[\d+\]=([a-z]+)|input\[\d+\]=([0-9]+)|input\[\d+\]=(\+|\-)/", $v, $m2
                            )
                          ){
                            if(isset($m[1]) && !empty($m[1])){
                              if(isset($m2[2]) && !empty($m2[2])){
                                $newMaches[$m[1]] = $m2[2];
                              }else if(isset($m2[3]) && !empty($m2[3])){
                                $newMaches[$m[1]] = $m2[3];
                              }
                            }
                          }
                        }
                      }
                    }

                    ?>

                    <?php
                    foreach ($data["additionalinfo"] as $value):
                      $description = strip_tags($value["description"]);

                      if($description=="yesno"):
                    ?>
                      <section>
                        <?=$value["title"]?> 
                        <section class="property">
                          <span class="has-property">
                            <?=(isset($newMaches[$value["idx"]]) && $newMaches[$value["idx"]]=="+") ? "<i class=\"fa fa-plus\"></i>" : "<i class=\"fa fa-minus\"></i>"?>
                          </span>
                        </section>
                      </section>
                    <?php endif; ?>

                    <?php if($description!="yesno"): 
                    $allFloors = (isset($newMaches[93])) ? $newMaches[93] : "0";
                    if($value["idx"]==93){
                      continue;
                    }
                    ?>
                    <section data-idx="<?=$value["idx"]?>">
                      <?=$value["title"]?> 
                      <section class="property">
                        <?php if($value["idx"]==36){ ?>
                        <span><?=(isset($newMaches[$value["idx"]])) ? $newMaches[$value["idx"]]." из ".$allFloors : 0?></span>
                        <?php }else{?>
                        <span><?=(isset($newMaches[$value["idx"]])) ? $newMaches[$value["idx"]] : 0?></span>
                        <?php }?>
                      </section>
                    </section>  
                    <?php endif; ?> 

                    <?php endforeach; ?> 

                    <?php if($data["productGetter"]["sale_type"]==54 || $data["productGetter"]["sale_type"]==55):?>
                    <section class="month-price-table">
                      <table style="width:100%; text-align:left">
                        <tr>
                          <td colspan="3" style="text-align:center">
                            <?php 
                            if($data["productGetter"]["sale_type"]==54){//day
                              echo "<span>В соответствующем месяце суточная цена</span>";
                            }else if($data["productGetter"]["sale_type"]==54){//day
                              echo "<span>В соответствующем месяце месячная цена</span>";
                            }else{
                              echo "<span>Цены</span>";
                            }
                            ?>
                          </td>
                        </tr>
                        <!-- <tr>
                          <td>Месяц</td>
                          <?php if(($data["productGetter"]["sale_type"]==54)):?>
                          <td>Эа сутки</td>
                          <?php endif; ?>
                          <?php if(($data["productGetter"]["sale_type"]==55)):?>
                          <td>Эа месяц</td>
                          <?php endif; ?>
                        </tr> -->
                        <?php                        
                        $pricebymonth = explode(",", $data["productGetter"]["pricebymonth"]);
                        $monthArray = array("jan"=>"Январь", "feb"=>"Февраль", "mar"=>"Март", "apr"=>"Апрель", "may"=>"Май", "jun"=>"Июнь", "jul"=>"Июль", "aug"=>"Август", "sep"=>"Сентябрь", "oct"=>"Октябрь", "nov"=>"Ноябрь", "dec"=>"Декабрь");
                        foreach ($monthArray as $key => $value):
                          $d = "day".$key;
                          $m = "month".$key;
                          

                          $input = preg_quote($d, '~');                         
                          $input2 = preg_quote($m, '~');                         
                          $day = preg_grep('~' . $input . '~', $pricebymonth);
                          $month = preg_grep('~' . $input2 . '~', $pricebymonth);

                          $day = explode("@@", implode("",$day));
                          $month = explode("@@", implode("",$month));

                          // $day = array_search("day".$key, $pricebymonth);
                          // $month = array_search("month".$key, $pricebymonth);
                        ?>                        
                        <tr>
                          <td><?=$value?></td>
                          <td><?php 
                          $pricexx = ($data["productGetter"]["sale_type"]==54) ? (int)$day[1] : (int)$month[1];
                          $realprice = 0;
                          $curname = "";
                          if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="gel"){
                            $realprice = (int)($pricexx*$_SESSION["_USD_"]);
                            $curname = "GEL";
                          }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="usd"){
                            $realprice = (int)$pricexx;
                            $curname = "USD";
                          }else if(isset($_SESSION["currency"]) && $_SESSION["currency"]=="rub"){
                            $realprice = (int)((int)($pricexx*$_SESSION["_USD_"])/$_SESSION["_RUB_"]);
                            $curname = "RUB";
                          }

                          echo $realprice;
                          ?> <?=$curname?></td>
                        </tr>
                        <?php endforeach; ?>
                      </table>
                    </section>
                    <?php endif; ?>

                    <p><strong>Адрес:</strong><br /> <?=strip_tags($data["productGetter"]["address"])?></p>

                  </section>
                </section>
                <section class="col-md-8 my-gallery">

                  <h4 class="row">
                    <div class="col-md-10  text-left">
                      <span><?=$data["productGetter"]["title"]?></span> 
                      <span class="text-primary" style="color: white">
                        <i class="fa fa-hand-o-right"></i>
                        ID <span><?=$data["productGetter"]["orderid"]?></span>
                      </span>
                    </div>
                  </h4>

                  <hr />

                  <section class="gallery">
                    <?php
                    $bigWidth = 765;
                    $bigHeight = 574;
                    $smallWidth = 100;
                    $smallHeight = 100;
                    $i = 1;
                    foreach ($data["photos"] as $pic) {
                      $firstChild = ($i==1) ? "firstChildImageGallery" : "empty";
                      echo sprintf(
                        "<a href=\"%s%s/image/loadimage?f=%s&w=%s&h=%s\" class=\"gallery-image-link %s\" data-size=\"%sx%s\" style=\"display: inline;\">", 
                        Config::WEBSITE, 
                        $_SESSION["LANG"],
                        base64_encode(Config::WEBSITE_.$pic["path"]), 
                        base64_encode($bigWidth),
                        base64_encode($bigHeight),
                        $firstChild, 
                        $bigWidth,
                        $bigHeight
                      );
                      echo sprintf(
                        "<img itemprop=\"thumbnail\" alt=\"%s\" src=\"%s%s/image/loadimage?f=%s&w=%s&h=%s\" class=\"fa loading  img-thumbnail\"  />", 
                        $data["productGetter"]["title"],
                        Config::WEBSITE, 
                        $_SESSION["LANG"],
                        base64_encode(Config::WEBSITE_.$pic["path"]), 
                        base64_encode($smallWidth),
                        base64_encode($smallHeight),
                        $smallWidth,
                        $smallHeight
                      );

                      echo "</a>";
                      $i=2;
                    }
                    ?>     
                  </section>

                  <hr />

                  <section class="row" style="margin-bottom: 20px;">
                    <section class="col-md-12">
                      <section class="text-muted text-justify">
                        <?=strip_tags($data["productGetter"]["description"],"<p><strong><a><ul><li><br>")?>

                        <p>&nbsp;</p>
                        <?php 
                        $db_views = new Database("products", array(
                          "method"=>"select_views",
                          "idx"=>$data["productGetter"]["idx"]
                        ));
                        $views = $db_views->getter();
                        ?>
                        <strong>Просмотров всево: <?=$views["totalviews"]?> Cегодня: <?=$views["todayviews"]?></strong>
                        <!-- Сегодния:  -->
                      </section>
                    </section>
                  </section>

                  <section class="row">
                    <section class="col-md-12 text-right">
                      <button onclick="window.history.back()" class="text-primary btn btn-success">Назад</button>
                    </section>
                  </section>

                </section>
              </section>

            </section>
          </section>

          
          <section class="list-group">
            <div id="googleMap" style="width:100%; height:500px"></div>
          </section>  


        </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>

  <div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">

          <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
          </div>

          <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>


            
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip">
                    
                </div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
            <div class="pswp__caption">
              <div class="pswp__caption__center">
              </div>
            </div>
          </div>

        </div>


    </div>

  <script type="text/javascript">
    (function() {

        var initPhotoSwipeFromDOM = function(gallerySelector) {

            var parseThumbnailElements = function(el) {
                var thumbElements = el.childNodes,
                    numNodes = thumbElements.length,
                    items = [],
                    el,
                    childElements,
                    thumbnailEl,
                    size,
                    item;

                for(var i = 0; i < numNodes; i++) {
                    el = thumbElements[i];

                    // include only element nodes 
                    if(el.nodeType !== 1) {
                      continue;
                    }

                    childElements = el.children;

                    size = el.getAttribute('data-size').split('x');

                    // create slide object
                    item = {
                        src: el.getAttribute('href'),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10),
                        author: el.getAttribute('data-author')
                    };

                    item.el = el; // save link to element for getThumbBoundsFn

                    if(childElements.length > 0) {
                      item.msrc = childElements[0].getAttribute('src'); // thumbnail url
                      if(childElements.length > 1) {
                          item.title = childElements[1].innerHTML; // caption (contents of figure)
                      }
                    }


                    var mediumSrc = el.getAttribute('data-med');
                    if(mediumSrc) {
                        size = el.getAttribute('data-med-size').split('x');
                        // "medium-sized" image
                        item.m = {
                            src: mediumSrc,
                            w: parseInt(size[0], 10),
                            h: parseInt(size[1], 10)
                        };
                    }
                    // original image
                    item.o = {
                        src: item.src,
                        w: item.w,
                        h: item.h
                    };

                    items.push(item);
                }

                return items;
            };

            // find nearest parent element
            var closest = function closest(el, fn) {
                return el && ( fn(el) ? el : closest(el.parentNode, fn) );
            };

            var onThumbnailsClick = function(e) {
                e = e || window.event;
                e.preventDefault ? e.preventDefault() : e.returnValue = false;

                var eTarget = e.target || e.srcElement;

                var clickedListItem = closest(eTarget, function(el) {
                    return el.tagName === 'A';
                });

                if(!clickedListItem) {
                    return;
                }

                var clickedGallery = clickedListItem.parentNode;

                var childNodes = clickedListItem.parentNode.childNodes,
                    numChildNodes = childNodes.length,
                    nodeIndex = 0,
                    index;

                for (var i = 0; i < numChildNodes; i++) {
                    if(childNodes[i].nodeType !== 1) { 
                        continue; 
                    }

                    if(childNodes[i] === clickedListItem) {
                        index = nodeIndex;
                        break;
                    }
                    nodeIndex++;
                }

                if(index >= 0) {
                    openPhotoSwipe( index, clickedGallery );
                }
                return false;
            };

            var photoswipeParseHash = function() {
                var hash = window.location.hash.substring(1),
                params = {};

                if(hash.length < 5) { // pid=1
                    return params;
                }

                var vars = hash.split('&');
                for (var i = 0; i < vars.length; i++) {
                    if(!vars[i]) {
                        continue;
                    }
                    var pair = vars[i].split('=');  
                    if(pair.length < 2) {
                        continue;
                    }           
                    params[pair[0]] = pair[1];
                }

                if(params.gid) {
                    params.gid = parseInt(params.gid, 10);
                }

                return params;
            };

            var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
                var pswpElement = document.querySelectorAll('.pswp')[0],
                    gallery,
                    options,
                    items;

                items = parseThumbnailElements(galleryElement);

                // define options (if needed)
                options = {

                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                    getThumbBoundsFn: function(index) {
                        // See Options->getThumbBoundsFn section of docs for more info
                        var thumbnail = items[index].el.children[0],
                            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                            rect = thumbnail.getBoundingClientRect(); 

                        return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
                    },

                    addCaptionHTMLFn: function(item, captionEl, isFake) {
                        if(!item.title) {
                            captionEl.children[0].innerText = '';
                            return false;
                        }
                        captionEl.children[0].innerHTML = item.title +  '<br/><small>Photo: ' + item.author + '</small>';
                        return true;
                    }
                    
                };


                if(fromURL) {
                    if(options.galleryPIDs) {
                        // parse real index when custom PIDs are used 
                        // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                        for(var j = 0; j < items.length; j++) {
                            if(items[j].pid == index) {
                                options.index = j;
                                break;
                            }
                        }
                    } else {
                        options.index = parseInt(index, 10) - 1;
                    }
                } else {
                    options.index = parseInt(index, 10);
                }

                // exit if index not found
                if( isNaN(options.index) ) {
                    return;
                }



                var radios = document.getElementsByName('gallery-style');
                for (var i = 0, length = radios.length; i < length; i++) {
                    if (radios[i].checked) {
                        if(radios[i].id == 'radio-all-controls') {

                        } else if(radios[i].id == 'radio-minimal-black') {
                            options.mainClass = 'pswp--minimal--dark';
                            options.barsSize = {top:0,bottom:0};
                            options.captionEl = false;
                            options.fullscreenEl = false;
                            options.shareEl = false;
                            options.bgOpacity = 0.85;
                            options.tapToClose = true;
                            options.tapToToggleControls = false;
                        }
                        break;
                    }
                }

                if(disableAnimation) {
                    options.showAnimationDuration = 0;
                }

                // Pass data to PhotoSwipe and initialize it
                gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);

                // see: http://photoswipe.com/documentation/responsive-images.html
                var realViewportWidth,
                    useLargeImages = false,
                    firstResize = true,
                    imageSrcWillChange;

                

                gallery.listen('gettingData', function(index, item) {
                    if( useLargeImages ) {
                        item.src = item.o.src;
                        item.w = item.o.w;
                        item.h = item.o.h;
                    } else {
                        // item.src = item.m.src;
                        // item.w = item.m.w;
                        // item.h = item.m.h;
                        item.src = item.o.src;
                        item.w = item.o.w;
                        item.h = item.o.h;
                    }
                });

                gallery.init();
            };

            // select all gallery elements
            var galleryElements = document.querySelectorAll( gallerySelector );
            for(var i = 0, l = galleryElements.length; i < l; i++) {
                galleryElements[i].setAttribute('data-pswp-uid', i+1);
                galleryElements[i].onclick = onThumbnailsClick;
            }

            // Parse URL and open gallery if it contains #&pid=3&gid=1
            var hashData = photoswipeParseHash();
            if(hashData.pid && hashData.gid) {
                openPhotoSwipe( hashData.pid,  galleryElements[ hashData.gid - 1 ], true, true );
            }
        };

        initPhotoSwipeFromDOM('.my-gallery');

    })();


var marker;
var map;

function initMap() {
  <?php 
  $explode = explode(":", $data["productGetter"]["location"]);
  $lat = (isset($explode[0])) ? $explode[0] : "41.633062500436054";
  $lon = (isset($explode[1])) ? $explode[1] : "41.636450378417976";
  ?>
  var myLatlng = new google.maps.LatLng(<?=(float)$lat?>,<?=(float)$lon?>);
  map = new google.maps.Map(document.getElementById('googleMap'), {
    zoom: 13,
    center: myLatlng
  });

  marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    icon:'/public/img/marker.png',
    title: 'Hello World!'
  });
}      
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=Config::GOOGLE_MAP_KEY?>&amp;callback=initMap"></script>


<?=$data['footer']?>

<script>
  <?php 
  $req = "/".$_SESSION["LANG"]."/view/?id=".(int)$_GET["id"];
  $actual_link = "http://".$_SERVER['HTTP_HOST'].$req; ?>
  $(".send-me-variants-title").hide();
  $("body").append("<div class='viberwhatup'><a href='https://api.whatsapp.com/send?phone=995593377776&text=<?=$actual_link?>%0A%0AМеня заинтересовала эта квартира и есть дополнительные вопросы'>whatsup</a><a href='viber://chat?number=%2B995593377776'>Viber</a></div>");
</script>
