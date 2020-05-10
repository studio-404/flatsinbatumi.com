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
            <section class="list-group-item" style="color: white;">

              <?=strip_tags($data["pageData"]["text"], "<h4><h3><p><br><span><strong><ul><li><i>")?>

              <div class="row">
                <div class="col-sm-12 text-right">
                  <a href="javascript:history.back(-1);" class="text-primary btn btn-success">Назад</a>
                </div>
              </div>

            </section>
          </section>
        




        </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>

<?=$data['footer']?>