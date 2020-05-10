<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/strip_output.php"); 
$l = new functions\l(); 
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
						<section class="list-group-item" style="color:white">
							<section class="output-message" style="background-color: rgba(20,202,33,.75);<?=(isset($_POST["usersname"]) ? " display: block" : "")?>"><?=$data["formMessage"]?></section>

							<form action="?addcomment=<?=time()?>" method="post" id="commentForm" enctype="multipart/form-data">
								<div class="form-group">
									<label>Ваше имя</label> 
									<input type="text" class="form-control" name="usersname" id="usersname" />
								</div> 
								<div class="form-group">
									<label>Фотография на память</label>
								</div> 
								<div>
									<label for="uploadbtn" class="btn btn-success">Загрузить файл</label> 
									<input type="file" name="uploadbtn" id="uploadbtn" class="btn btn-success" style="opacity: 0; z-index: -1;">
								</div> 
								<div class="form-group">
									<label>Отзыв</label> 
									<textarea rows="4" class="form-control" name="comment" id="comment" style="resize: none;"></textarea>
								</div> 
								<button type="submit" class="btn btn-success addComment">Отправить</button> 
								<a href="<?=Config::WEBSITE?>" class="text-primary btn btn-success">Назад</a>
							</form>

						</section>


					</section>

					<section class="list-group">

						<?php
						foreach ($data["comments"] as $va) :
							$file_path = Config::WEBSITE_."/public/filemanager/comments/".$va["photo"];
							$image = sprintf(
								"%s%s/image/loadimage?f=%s&w=%s&h=%s",
								Config::WEBSITE,
								strip_output::index($_SESSION['LANG']),
								base64_encode($file_path),
								base64_encode(100),
								base64_encode(100)
							);
						?>
						<section class="list-group-item" style="color: white">
							<div>
								<div class="media">
									<div class="media-left">
										<img src="<?=$image?>"alt="<?=htmlentities(strip_tags($va["firstname"]))?>" class="media-object" />
									</div> 
									<div class="media-body">
										<h4 class="media-heading" style="text-align:left"><?=htmlentities(strip_tags($va["firstname"]))?></h4> 
										<div><?=htmlentities(strip_tags($va["comment"]))?></div>
									</div>
								</div>
							</div>
						</section>
						<?php
						endforeach;
						?>

						

						<section class="list-group-item">
							<a href="<?=Config::WEBSITE?>" class="text-primary btn btn-success btn-block">Назад</a>
						</section>

					</section>	


				</section>
			</section>
		</section>

		<a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
	</main>

<?=$data['footer']?>
