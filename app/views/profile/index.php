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
                  <a href="?view=home">
                    Мои заявления 
                    <?php 
                    if(
                      !functions\request::index("GET","view") || 
                      functions\request::index("GET","view")=="home"
                    ): 
                    ?>
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    <?php endif; ?>
                  </a>
                </li>
                <li>
                  <a href="?view=edit">
                  Профиль
                  <?php 
                  if(
                    functions\request::index("GET","view") && 
                    functions\request::index("GET","view")=="edit"
                  ): 
                  ?>
                  <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                  <?php endif; ?>
                  </a>
                </li>
                <li>
                  <a href="?view=update-password">
                  Обновить пароль
                  <?php 
                  if(
                    functions\request::index("GET","view") &&
                    functions\request::index("GET","view")=="update-password"
                  ): 
                  ?>
                  <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                  <?php endif; ?>
                  </a>
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
             
                <?php 
                if(functions\request::index("GET","view") && functions\request::index("GET","view")!="home")
                {
                  switch(functions\request::index("GET","view"))
                  {
                    case "edit":
                      echo "<section class=\"list-group-item flat\" style=\"background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white\">";
                      echo $data["editprofile"];
                      echo "</section>";
                      break;
                    case "update-password":
                      echo "<section class=\"list-group-item flat\" style=\"background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white\">";
                      echo $data["editpassword"]; 
                      echo "</section>";
                      break;
                  }
                }
                else
                {
                  ?>
                  <section class="list-group-item">
                    <a href="/ru/rentapartment" class="text-primary btn btn-success"><i class="fa fa-building-o"></i>&emsp;Сдать квартиру</a>
                  </section>

                  <?php foreach ($data["usersProducts"] as $item) : ?>
                  <div class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
                    <section class="row">
                        <section class="flat-price" <?=($item["showwebsite"]==1) ? 'style="background-color:red"' : ''?>>
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
                        </section> 
                        <section class="col-sm-4">
                          <img src="<?=Config::WEBSITE.$_SESSION["LANG"]?>/image/loadimage?f=<?=base64_encode(Config::WEBSITE_.$item["photo"])?>&w=<?=base64_encode("220")?>&h=<?=base64_encode("220")?>" class="loading img-responsive img-thumbnail" />
                        </section> 
                        
                        <section class="col-sm-8">
                          <section class="flat-labels">
                            <span><?=$item["sale_type"]?></span> 
                            <span><?=$item["rooms"]?></span> 
                            <span><?=$item["type"]?></span>
                          </section> 
                          <h4 style="text-align: left">
                            <span><?=$item["title"]?></span> 
                            <span class="text-primary">
                              <i class="fa fa-hand-o-right"></i>
                              ID <span><?=$item["orderid"]?></span>
                            </span>
                          </h4>
                          <section class="text-muted">
                            <?=$string->cut(strip_tags($item["description"]), 250)?>
                            <p style="padding:20px 0; font-size: 18px;">
                              <a href="/ru/view/<?=urlencode(strip_tags($item["title"]))?>/?id=<?=(int)$item["idx"]?>" style="color: white">Посмотреть</a> / 
                              <a href="/ru/updateflat/?id=<?=(int)$item["idx"]?>" style="color: white">Редактировать</a> / 
                              <a href="/ru/profile?remove=<?=(int)$item["idx"]?>" style="color: white">Удалить</a>
                            </p>
                          </section> 
                          
                        </section>
                      </section>
                    </div>
                  <?php
                  endforeach;
                }
                ?>
              

          </section>
          </section>

        </section>
      </section>
    </section>
  </main>

<?=$data['footer']?>