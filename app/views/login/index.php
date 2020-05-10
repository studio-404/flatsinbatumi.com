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
                <h4 class="text-center text-uppercase">Прошу вас заполнить анкету</h4>
              </section>

              <form action="javascript:void(0)" method="post">
                <div class="output-message" style="background-color: rgba(20, 202, 33, 0.75); clear:both;">&nbsp;</div>
                <div class="form-group">
                  <label>Имя пользователя ( Эл. адрес )*</label> 
                  <input type="text" class="form-control" name="usersname" id="usersname" style="border: 1px solid hsla(0,0%,100%,.43)!important;" autocomplete="off" />
                </div> 

                <div class="form-group">
                  <label>Пароль*</label> 
                  <input type="password" class="form-control" name="password" id="password" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <button class="btn btn-success pull-right btn-block btn-lg loginButton" style="font-size: 18px;">Вход</button>
                <div style="clear:both"></div>
              </form>
              <p>&nbsp;</p>
              <p>Не зарегистрирован: <a href="/<?=$_SESSION["LANG"]?>/registration" style="color: white; text-decoration: underline">Зарегистрироваться</a></p>
              <p>Забыли пароль?: <a href="/<?=$_SESSION["LANG"]?>/recover" style="color: white; text-decoration: underline">Пароль восстановления</a></p>
            </section>
          </section>
        </section>
        <section class="col-md-6 right">
          <section class="list-group-item" style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32); color:white">
            <?=strip_tags($data["pageData"]["text"],"<p><strong><h1><h2><h3><ul><ol><li><a><i>")?>
          </section>
        </section>
      </section>
    </section>

  </main>

<?=$data['footer']?>