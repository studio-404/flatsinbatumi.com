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
				<?php 
				echo $data["leftnav"];
				?>
				
				<section class="col-md-8 right">
					<section class="list-group" style="position: relative;">
						<section class="list-group-item" style="color:white">
							<h1 style="margin: 0px; padding:0px; text-align: center;"><?=strip_output::index($data['pageData']['title'])?></h1>
							<p style="font-size: 18px;"><?=strip_output::index($data['pageData']['text'])?></p>
						</section>

						<div style="background: white; width: 100%; padding-top:10px;">
							<iframe src="http://flatsinbatumi.com/ru/pogodapatumiplugin" frameborder="0" style="margin: 0; padding: 5px; width: calc(100% - 10px); border: 0px; min-height:690px; overflow: hidden;"></iframe>
						</div>

						<div class="clearer" style="clear:both;"></div>

						<div style="background-color: rgba(18,115,185,.7); border: 1px solid rgba(42,74,109,.32);">

							<div class="clearer" style="clear:both; margin-top: 15px;"></div>

							<button onclick="window.history.back()" class="text-primary btn btn-success" style="margin: 0 0 15px 15px;">Назад</button>

						</div>
					</section>



				</section>
			</section>
		</section>

		
	</main>

	<script>
		// $(document).ready(function(){
		// 	$('#loadx').load('http://flatsinbatumi.com/ru/pogodapatumiplugin');
		// });
	</script>

<?=$data['footer']?>
