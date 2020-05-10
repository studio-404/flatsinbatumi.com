<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
$l = new functions\l(); 
echo $data['headerModule']; 
echo $data['headertop']; 

// echo "<pre>";
// print_r($data["pageData"]);
// echo "</pre>"; 
?>

<main>
	<?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?=$actual_link?>" target="_blank" class="facebook-share"><i class="fa fa-facebook-square fa-2x"></i></a>
		<section class="container">
			<section class="row containerRow">
				<?=$data["leftnav"]?>
				
				<section class="col-md-8 right">


					<section class="list-group">
						<section class="list-group-item" style="color: white">

							<h4 class="text-center">Связаться с нами:</h4>

							<ul class="list-group nobullets" style="margin:0 0 10px 0">
								<?php 
								if(isset($data["contactinfo"]) && count($data["contactinfo"])>0):
									foreach ($data["contactinfo"] as $val):
								?>
									<li class="list-group-item">
										<i class="<?=htmlentities($val["classname"])?>"></i> <?=$val["title"]?>
									</li> 
								<?php
									endforeach; 
								endif; 
								?>
							</ul>

							<div class="row">
								<div class="col-md-12 padding-lg">
									
									<div class="text-justify text-muted">
										<?=strip_tags($data["pageData"]["text"], "<h4><h3><p><br><span><strong><ul><li><i><table><thead><th><tbody><tr><td><hr>")?>
                    				</div>
								</div>
							</div>




							<div class="row">
								<div class="col-sm-12 text-right">
									<a href="<?=Config::WEBSITE?>" class="text-primary btn btn-success router-link-active">
										На главную
									</a>
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
