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
                <section class="col-sm-4 filter-select-item">
                  <select title="filter" class="form-control" id="fsale_type">
                    <option value="">Тип сделки</option> 
                    <?php 
                    foreach($data["salestype"] as $sale) : 
                    $selected = (isset($_SESSION["query"]["sale_type"]) && $_SESSION["query"]["sale_type"]==$sale["idx"]) ? 'selected="selected"' : '';
                    $selected = ($selected=="" && $sale["idx"]==$data["selected_sale_type"]) ? 'selected="selected"' : $selected;
                    ?>                    
                    <option value="<?=$sale["idx"]?>" <?=$selected?>><?=$sale["title"]?></option> 
                    <?php endforeach; ?>
                  </select>
                </section> 
                <section class="col-sm-4 filter-select-item">
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
                <section class="col-sm-4 filter-select-item">
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
              </section> 
              <br> 
              <section class="row">
                <section class="col-sm-2 filter-select-item">
                  <input type="number" min="5" title="цена от - USD" id="fprice_from" placeholder="цена от - USD" class="form-control" value="<?=(isset($_SESSION["query"]["price_from"]) ? $_SESSION["query"]["price_from"] : '')?>">
                </section> 
                <section class="col-sm-2 filter-select-item">
                  <input type="number" min="5" title="цена до - USD" id="fprice_to" placeholder="цена до - USD" class="form-control" value="<?=(isset($_SESSION["query"]["price_to"]) ? $_SESSION["query"]["price_to"] : '')?>">
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
                        $or = "";
                      }
                      ?>

                      <input type="hidden" name="itemPerPage" id="itemPerPage" value="<?=$data["itemPerPage"]?>" />
                      <input type="hidden" name="forderby" id="forderby" value="<?=$or?>" />
                      <input type="hidden" name="pn" id="pn" value="<?=(isset($_SESSION["query"]["pn"]) ? (int)$_SESSION["query"]["pn"]+1 : 2)?>" />
                      <button class="btn btn-success pull-right change-order-list">
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

              <div id="results">
              <?php 
              foreach ($data["products"] as $item) : 
                // $additionaldata = explode(",", $item["additional_data"]);
                // $ploshad_explode = explode("input[35]=", @$additionaldata[0]);
                // $etaj_explode = explode("input[36]=", @$additionaldata[1]);
                // $totaletaj_explode = explode("input[93]=", @$additionaldata[2]);
                // $spalni_explode = explode("input[37]=", @$additionaldata[3]);

                $ploshad_explode = "";
                if(preg_match_all("/input\[35\]\=(\d+)/", $item["additional_data"], $ploshad_explode)){
                  $ploshad_explode = $ploshad_explode[1][0];
                }

                $etaj_explode = "";
                if(preg_match_all("/input\[36\]\=(\d+)/", $item["additional_data"], $etaj_explode)){
                  $etaj_explode = $etaj_explode[1][0];
                }

                $totaletaj_explode = "";
                if(preg_match_all("/input\[93\]\=(\d+)/", $item["additional_data"], $totaletaj_explode)){
                  $totaletaj_explode = $totaletaj_explode[1][0];
                }

                $spalni_explode = "";
                if(preg_match_all("/input\[37\]\=(\d+)/", $item["additional_data"], $spalni_explode)){
                  $spalni_explode = $spalni_explode[1][0];
                }                
              ?>
                  <div class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
                    <section class="row">
                        <section class="flat-price" style="height: auto;">
                          <?php 
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
                          ?>
                          <span><?=$realprice?></span> <?=$curname?>
                          <?php 
                          if($item["sale_type"]=="Продажа" && (int)$ploshad_explode>0){
                            $m2price = round($realprice / (int)$ploshad_explode);
                            ?>
                            <br /><span style="font-size: 12px;"><?=$m2price?> <?=$curname?> / м<sup>2</sup></span>
                            <?php
                          }
                          ?>
                        </section> 
                        <section class="col-sm-4 g-image-container g-container<?=$item["idx"]?>" data-loading="false">
                          <div class="g-infox">
                            <span class="g-activex">1</span> - 
                            <span class="g-maxx"><?=$item["photoCount"]?></span>
                          </div>
                          <div class="g-leftar g-arrow" data-idx="<?=$item["idx"]?>" data-active="1" data-max="<?=$item["photoCount"]?>"><i class="fa fa-angle-left"></i></div>
                          <a href="/ru/view/<?=urlencode(strip_tags($item["title"]))?>/?id=<?=(int)$item["idx"]?>" class="g-image-sliderx">
                          <img src="<?=Config::WEBSITE.$_SESSION["LANG"]?>/image/loadimage?f=<?=base64_encode(Config::WEBSITE_.$item["photo"])?>&w=<?=base64_encode("220")?>&h=<?=base64_encode("220")?>" class="loading img-responsive img-thumbnail" />
                          </a>
                          <div class="g-rightar g-arrow" data-idx="<?=$item["idx"]?>" data-active="1" data-max="<?=$item["photoCount"]?>"><i class="fa fa-angle-right"></i></div>
                        </section> 
                        
                        <section class="col-sm-8">
                          <a href="/ru/view/<?=urlencode(strip_tags($item["title"]))?>/?id=<?=(int)$item["idx"]?>" style="color:white; text-decoration: none;">
                          <section class="flat-labels">
                            <!-- <span><?=$item["sale_type"]?></span> -->
                            <span><?=$item["type"]?></span>
                            <span><?=$item["rooms"]?></span>
                            <span data-ex="<?=$item["additional_data"]?>"><?=(int)@$spalni_explode?> Спальни</span>
                            <span><?=(int)@$ploshad_explode?> м<sup>2</sup></span>
                            <span><?=(int)@$etaj_explode?>/<?=(int)@$totaletaj_explode?> зтаж</span>
                          </section> 
                          <h4 style="text-align: left; line-height: 22px;" class="g-desktop-width400 g-margin-top-desktop40">
                            <span><?=$item["title"]?></span> 
                            <span class="text-primary" style="margin: 10px 0 0 5px;">
                              <i class="fa fa-hand-o-right"></i>&nbsp;
                              ID <span><?=$item["orderid"]?></span>
                            </span>
                          </h4>
                          <section class="text-muted margin-top20">
                            <?php 
                            // $string->cut(strip_tags($item["description"]), 120);
                            ?>
                            <p style="margin-top: 10px; margin-bottom: 0"><strong>Адрес: </strong><?=$item["address"]?></p>
                            <?php 
                            $db_views = new Database("products", array(
                              "method"=>"select_views",
                              "idx"=>$item["idx"]
                            ));
                            $views = $db_views->getter();
                            ?>
                            <p>&nbsp;</p>
                            <strong>Просмотров всево: <?=$views["totalviews"]?> Cегодня: <?=$views["todayviews"]?> 
                          <!-- <em onclick="location.href='/ru/searchonmap/?idx=<?=$item["idx"]?>'; return false;">Показать на карте</em> -->
                          </strong>

                          </section> 
                          </a>
                        </section>
                      </section>
                    </div>
                  <?php
                  endforeach;
                ?>
                </div>

              <div class="list-group-item loadergif" style="display:none; text-align: center; color: white;"><i class="fa fa-circle-o-notch fa-spin"></i></div>

              <section class="action-button loadmoreitems" data-loaded="<?=$data["itemPerPage"]?>">Показать ещё квартиры</section>

            </section>
          </section>
              





        </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>

<?=$data['footer']?>