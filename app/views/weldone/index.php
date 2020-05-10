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
        <section class="col-md-4 left">
          <section class="list-group">
            <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
              <h4 class="usersh4">Пользователь: giorgi gvazava</h4>
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
                  
                    <h3 style="margin:0 0 10px 0"><?=strip_tags($data["pageData"]["text"])?></h3>

                    <!-- <p>Ваша объект будет показано в ближайшее 24 часа.</p> -->
                    <p style="font-size: 18px; padding:20px 0">Ваш объект будет показан на сайт как только наш оператор одобрит вашу заявку (в течение 24 часа)</p>

                    <a href="/ru/profile" class="text-primary btn btn-success"><i class="fa fa-long-arrow-left"></i>&emsp;Назад</a>

                    
                  </section>
              

          </section>
          </section>

        </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>



<?=$data['footer']?>