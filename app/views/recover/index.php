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
        <section class="col-md-6 left showonmobileleft">
          <section class="list-group">
            <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
              <section class="col-xs-12">
                <h4 class="text-center text-uppercase"><?=$data["pageData"]["title"]?></h4>
              </section>

              <form action="javascript:void(0)" method="post" id="first-step">
                <div class="output-message" style="background-color: rgba(20, 202, 33, 0.75); clear:both;">&nbsp;</div>
                <div class="form-group">
                  <label>Эл. адрес *</label> 
                  <input type="text" class="form-control" name="usersname" id="usersname" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <button class="btn btn-success pull-right btn-block btn-lg recoverButton" style="font-size: 18px;">Восстановление</button>
                <div style="clear:both"></div>
              </form>

              
              <form action="javascript:void(0)" method="post" id="second-step" style="display: none">
                <div class="output-message" style="background-color: rgba(20, 202, 33, 0.75); clear:both;">&nbsp;</div>
                <div class="form-group">
                  <label>Код восстановления пароля</label> 
                  <input type="text" class="form-control" name="updatepassword" id="updatepassword" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 
                <div class="form-group">
                  <label>Новый пароль</label> 
                  <input type="password" class="form-control" name="newpass" id="newpass" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 
                <div class="form-group">
                  <label>Подтвердите новый пароль</label> 
                  <input type="password" class="form-control" name="confirmnewpass" id="confirmnewpass" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <button class="btn btn-success pull-right btn-block btn-lg recoverButtonStep2" style="font-size: 18px;">Восстановление</button>
                <div style="clear:both"></div>
              </form>


              <p>&nbsp;</p>
              <p>Уже зарегистрированы: <a href="/<?=$_SESSION["LANG"]?>/login" style="color: white; text-decoration: underline">Вход</a></p>
              <p>Не зарегистрирован: <a href="/<?=$_SESSION["LANG"]?>/registration" style="color: white; text-decoration: underline">Зарегистрироваться</a></p>
              
            </section>
          </section>
        </section>
        <section class="col-md-6 right">
          <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
            <?=$data["pageData"]["text"]?>
          </section>
        </section>
      </section>
    </section>

   
  </main>

<?=$data['footer']?>