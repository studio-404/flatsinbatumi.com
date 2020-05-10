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
                  <label>Эл. адрес *</label> 
                  <input type="text" class="form-control" id="email" name="email"  style="border: 1px solid hsla(0,0%,100%,.43)!important;" value="" autocomplete="off" />
                </div> 

                <div class="form-group">
                  <label>Имя Фамилия*</label> 
                  <input type="text" class="form-control" id="firstlastname" name="firstlastname" style="border: 1px solid hsla(0,0%,100%,.43)!important;" value="" />
                </div>

                <div class="form-group">
                  <label>Мобильный*</label> 
                  <input type="text" class="form-control" id="mobile" name="mobile" value=""  style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <div class="form-group">
                  <label>Пароль*</label> 
                  <input type="password" class="form-control" id="password" name="password"  style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <div class="form-group">
                  <label>Подтвердите Пароль*</label> 
                  <input type="password" class="form-control" id="comfirmpassword" name="comfirmpassword"  style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                </div> 

                <button class="btn btn-success pull-right btn-block btn-lg registrationButton" style="font-size: 18px;">Зарегистрироваться</button>
                <div style="clear:both"></div>
              </form>
              <p>&nbsp;</p>
              <p>Уже зарегистрированы: <a href="/<?=$_SESSION["LANG"]?>/login" style="color: white; text-decoration: underline">Вход</a></p>
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