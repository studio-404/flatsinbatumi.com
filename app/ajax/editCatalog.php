<?php 
class editCatalog
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/makeForm.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/string.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$idx = functions\request::index("POST","idx");
		$lang = functions\request::index("POST","lang");
		$random = functions\string::random(25);

		if($idx == "" || $lang=="")
		{
			$this->out = array(
				"Error" => array(
					"Code"=>1, 
					"Text"=>"მოხდა შეცდომა !",
					"Details"=>"!"
				)
			);
		}else{
			$Database = new Database('products', array(
					'method'=>'selectById', 
					'idx'=>$idx, 
					'lang'=>$lang
			));
			$output = $Database->getter();

			$photos = new Database('photos', array(
				'method'=>'selectByParent', 
				'idx'=>$idx, 
				'lang'=>$lang, 
				'type'=>"products"
			));
			$pictures = $photos->getter();


			$form = functions\makeForm::open(array(
				"action"=>"?",
				"method"=>"post",
				"id"=>"",
				"id"=>"",
			));

			$form .= "<input type=\"hidden\" name=\"language\" id=\"language\" value=\"".$_SESSION['LANG']."\" />";

			$form .= functions\makeForm::label(array(
				"id"=>"dateLabel", 
				"for"=>"date", 
				"name"=>"დამატების თარიღი",
				"require"=>""
			));

			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"date", 
				"name"=>"date",
				"value"=>date("d-m-Y", $output["date"])
			));

			$form .= functions\makeForm::label(array(
				"id"=>"insertAdminLabel", 
				"for"=>"insertAdmin", 
				"name"=>"მონაცემი დაამატა ( ელ-ფოსტა / სახელი გვარი / მობილური )",
				"require"=>""
			));
			$admin_data = sprintf("%s / %s / %s", $output["admin_email"], $output["admin_firstname"], $output["admin_phone"]);
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"insertAdmin", 
				"name"=>"insertAdmin",
				"readonly"=>true,
				"value"=>$admin_data
			));


			$form .= functions\makeForm::label(array(
				"id"=>"orderLabel", 
				"for"=>"orderid", 
				"name"=>"განლაგების ID",
				"require"=>""
			));

			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"orderid", 
				"name"=>"orderid",
				"value"=>$output["orderid"]
			));


			$form .= functions\makeForm::label(array(
				"id"=>"titleLabel", 
				"for"=>"title", 
				"name"=>"დასახელება",
				"require"=>""
			));
		
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"title", 
				"name"=>"title",
				"value"=>$output["title"]
			));


			/* Sale type Start */
			$db_salestype = new Database("modules", array(
				"method"=>"selectModuleByType", 
				"type"=>"salestype"
			));
			$salestype = $db_salestype->getter(); 
			$options = array();
			foreach ($salestype as $value) {
				$options[$value['idx']] = $value['title'];
			}

			$form .= functions\makeForm::label(array(
				"id"=>"saleTypeLabel", 
				"for"=>"saleType", 
				"name"=>"გაყიდვის ტიპი",
				"require"=>""
			));

			$form .= functions\makeForm::select(array(
				"id"=>"saleType",
				"choose"=>"აირჩიეთ გაყიდვის ტიპი",
				"options"=>$options,
				"selected"=>$output["sale_type"],
				"disabled"=>"true"
			));
			/* Sale type End */

			/* rooms Start */
			$db_rooms = new Database("modules", array(
				"method"=>"selectModuleByType", 
				"type"=>"rooms"
			));
			$rooms = $db_rooms->getter(); 
			$options = array();
			foreach ($rooms as $value) {
				$options[$value['idx']] = $value['title'];
			}

			$form .= functions\makeForm::label(array(
				"id"=>"roomsLabel", 
				"for"=>"rooms", 
				"name"=>"ოთახები",
				"require"=>""
			));

			$form .= functions\makeForm::select(array(
				"id"=>"rooms",
				"choose"=>"აირჩიეთ ოთახების რაოდენობა",
				"options"=>$options,
				"selected"=>$output["rooms"],
				"disabled"=>"false"
			));
			/* rooms End */

			/* type Start */
			$db_type = new Database("modules", array(
				"method"=>"selectModuleByType", 
				"type"=>"type"
			));
			$type = $db_type->getter(); 
			$options = array();
			foreach ($type as $value) {
				$options[$value['idx']] = $value['title'];
			}

			$form .= functions\makeForm::label(array(
				"id"=>"typeLabel", 
				"for"=>"type", 
				"name"=>"უძრავი ქონების ტიპი",
				"require"=>""
			));

			$form .= functions\makeForm::select(array(
				"id"=>"type",
				"choose"=>"აირჩიეთ ტიპი",
				"options"=>$options,
				"selected"=>$output["type"],
				"disabled"=>"false"
			));
			/* type End */


			$form .= functions\makeForm::label(array(
				"id"=>"priceLabel", 
				"for"=>"price", 
				"name"=>"ღირებულება",
				"require"=>""
			));
		
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"price", 
				"name"=>"price",
				"value"=>$output["price"]
			));


			if($output["sale_type"]==54 || $output["sale_type"]==55)
			{
				/* price by month START */
				$form .= functions\makeForm::label(array(
					"id"=>"pricebymonthLabel", 
					"for"=>"pricebymonth", 
					"name"=>"ღირებულება თვეების მიხედვით",
					"require"=>""
				));

				$pricebymonth = explode(",", $output["pricebymonth"]);
				$priceMonthOut = array();
				foreach ($pricebymonth as $value) {
					$explode = explode("@@", $value);
					if(isset($explode[0]) && isset($explode[1]))
						$priceMonthOut[$explode[0]] = $explode[1];
				}

				$form .= "<table class='striped' id='pricebymonthtable' style='margin-bottom: 20px;'>";
				$form .= "<thead>";
				$form .= "<tr>";
				$form .= "<th>თვე</th>";
				if($output["sale_type"]==54){
					$form .= "<th>დღის ფასი</th>";
				}
				if($output["sale_type"]==55){
					$form .= "<th>თვის ფასი</th>";
				}
				$form .= "</tr>";
				$form .= "</thead>";
				$form .= "<tbody>";
				$monthArray = array("jan"=>"იანვარი", "feb"=>"თებერვალი", "mar"=>"მარტი", "apr"=>"აპრილი", "may"=>"მაისი", "jun"=>"ივნისი", "jul"=>"ივლისი", "aug"=>"აგვისტო", "sep"=>"სექტემბერი", "oct"=>"ოქტომბერი", "nov"=>"ნოემბერი", "dec"=>"დეკემბერი");
				foreach ($monthArray as $key => $value):
				$form .= "<tr>";
				$form .= sprintf("<td>%s</td>", $value);
				if($output["sale_type"]==54){
					$form .= sprintf(
						"<td style=\"padding: 0 15px 0 0\">%s</td>", 
						strip_tags(functions\makeForm::inputText(array(
							"placeholder"=>"", 
							"id"=>"day".$key, 
							"name"=>"day".$key,
							"value"=>(isset($priceMonthOut["day".$key])) ? $priceMonthOut["day".$key] : 0
						)),"<input>")
					);
				}

				if($output["sale_type"]==55){
					$form .= sprintf(
						"<td style=\"padding: 0 15px 0 0\">%s</td>", 
						strip_tags(functions\makeForm::inputText(array(
							"placeholder"=>"", 
							"id"=>"month".$key, 
							"name"=>"month".$key,
							"value"=>(isset($priceMonthOut["month".$key])) ? $priceMonthOut["month".$key] : 0
						)),"<input>")
					);
				}
				$form .= "</tr>";
				endforeach;		
				$form .= "</tbody>";
				$form .= "</table>";
				/* price by month END */
			}

			/* Additional Info START */
			$form .= functions\makeForm::label(array(
				"id"=>"additionalinfoLabel", 
				"for"=>"additionalinfo", 
				"name"=>"დამატებიტი ინფორმაცია",
				"require"=>""
			));
			$db_additionalinfo = new Database("modules", array(
				"method"=>"selectModuleByType", 
				"type"=>"additionalinfo",
				"order"=>"date",
				"by"=>"DESC",
				"from"=>0, 
				"num"=>100
			));
			$additionalinfo = $db_additionalinfo->getter();

			$newMaches = array();

			if(
				preg_match_all(
					"/input\[\d+\]=[a-z]+|input\[\d+\]=[0-9]+|input\[\d+\]=\+|input\[\d+\]=\-/", 
					$output["additional_data"], $matches
				)
			){
				if(isset($matches[0]))
				{
					foreach ($matches[0] as $v) {
						if(
							preg_match(
								"/input\[(\d+)\]/", $v, $m
							) &&
							preg_match(
								"/input\[\d+\]=([a-z]+)|input\[\d+\]=([0-9]+)|input\[\d+\]=(\+|\-)/", $v, $m2
							)
						){
							if(isset($m[1]) && !empty($m[1])){
								if(isset($m2[2]) && !empty($m2[2])){
									$newMaches[$m[1]] = $m2[2];
								}else if(isset($m2[3]) && !empty($m2[3])){
									$newMaches[$m[1]] = $m2[3];
								}
							}
						}
					}
				}
			}
			

			$form .= "<table class='striped' id='additionalinfotable' style='margin-bottom: 20px;'>";
			$form .= "<thead>";
			$form .= "<tr>";
			$form .= "<th>დასახელება</th>";
			$form .= "<th>მნიშვნელობა</th>";
			$form .= "</tr>";
			$form .= "</thead>";
			$form .= "<tbody>";
			
			foreach ($additionalinfo as $value):
			$form .= "<tr>";
			$form .= sprintf("<td>%s</td>", $value["title"]);
			//$(".chooseyesorno").material_select();
			$description = strip_tags($value["description"]);
			if($description=="yesno")
			{
				$form .= sprintf(
					"<td style=\"padding: 0 15px 0 0\">%s</td>",
					functions\makeForm::select(array(
						"id"=>"input[".$value["idx"]."]",
						"name"=>"input[".$value["idx"]."]",
						"classname"=>"additionalinfos chooseyesorno",
						"choose"=>"აირჩიეთ",
						"options"=>array(
							"+"=>"+",
							"-"=>"-"
						),
						"selected"=>(isset($newMaches[$value["idx"]])) ? $newMaches[$value["idx"]] : "+",
						"disabled"=>"false"
					))
				);
			}else{
				$form .= sprintf(
					"<td style=\"padding: 0 15px 0 0\">%s</td>", 
					strip_tags(functions\makeForm::inputText(array(
						"placeholder"=>"", 
						"id"=>"input[".$value["idx"]."]", 
						"classname"=>"additionalinfos", 
						"name"=>"input[".$value["idx"]."]",
						"value"=>(isset($newMaches[$value["idx"]])) ? $newMaches[$value["idx"]] : 0
					)),"<input>")
				);
			}
			$form .= "</tr>";
			endforeach;		
			$form .= "</tbody>";
			$form .= "</table>";
			/* Additional Info END */


			$form .= functions\makeForm::label(array(
				"id"=>"shortDescriptionLabel", 
				"for"=>"shortDescription", 
				"name"=>"მოკლე აღწერა",
				"require"=>""
			));

			$form .= functions\makeForm::textarea(array(
				"id"=>"shortDescription",
				"name"=>"shortDescription",
				"placeholder"=>"",
				"value"=>$output["short_description"]
			));

			$form .= functions\makeForm::label(array(
				"id"=>"longDescriptionLabel", 
				"for"=>"longDescription", 
				"name"=>"ვრცელი აღწერა",
				"require"=>""
			));

			$form .= functions\makeForm::textarea(array(
				"id"=>"longDescription",
				"name"=>"longDescription",
				"placeholder"=>"",
				"value"=>$output["description"]
			));

			$form .= functions\makeForm::label(array(
				"id"=>"addressLabel", 
				"for"=>"address", 
				"name"=>"მისამართი",
				"require"=>""
			));
		
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"address", 
				"name"=>"address",
				"value"=>$output["address"]
			));

			$form .= functions\makeForm::label(array(
				"id"=>"locationsLabel", 
				"for"=>"locations", 
				"name"=>"ადგილმდებარეობა/ადგილმდებარეობები",
				"require"=>""
			));
			
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>"", 
				"id"=>"locations", 
				"name"=>"locations",
				"readonly"=>true,
				"value"=>$output["location"]
			));

			$form .= "<script type=\"text/javascript\">
			window.onmessage = function(e){
			    $(\"#locations\").val(e.data);
			};
			</script>";
			
			$_SESSION["token"] = $random;
			$form .= sprintf(
				"<iframe class=\"locationsMap\" src=\"%s?token=%s&l=%s\"></iframe>",
				Config::PUBLIC_FOLDER."googleMap/edit.php",
				$random,
				$output["location"]
			);

			
	

			$form .= functions\makeForm::label(array(
				"id"=>"visibilitiTypeLabel", 
				"for"=>"choosevisibiliti", 
				"name"=>"ხილვადობა",
				"require"=>""
			));

			$form .= functions\makeForm::select(array(
				"id"=>"choosevisibiliti",
				"choose"=>"აირჩიეთ ხილვადობა",
				"options"=>array(
					1=>"დამალვა",
					2=>"გამოჩენა"
				),
				"selected"=>1,
				"disabled"=>"false"
			));


			$form .= "<script type=\"text/javascript\">";
			$form .= sprintf(
				"$('#choosevisibiliti').find('option[value=\"%d\"]').prop('selected', true);",
				$output['showwebsite']
			);
			$form .= "$('#choosevisibiliti').material_select();";
			$form .= "</script>";

			$form .= "<div class=\"row\" id=\"photoUploaderBox\" style=\"margin:0 -10px\">";

			if(count($pictures)){
				$i = 2;
				
				foreach($pictures as $picture) {
					$form .= "<div class=\"col s4 imageItem\" id=\"img".$i."\">
						<div class=\"card\">
				    		<div class=\"card-image waves-effect waves-block waves-light\">
				    			<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"".$picture['path']."\" />
				      			<img class=\"activator\" src=\"".Config::WEBSITE.Config::MAIN_LANG."/image/loadimage?f=".Config::WEBSITE_.$picture["path"]."&w=215&h=173&fromAdmin=true\" />
				    		</div>

				    		<div class=\"card-content\">
			                	<p>
			                		<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img".$i."')\" class=\"large material-icons\">mode_edit</a>
			                		<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img".$i."')\" class=\"large material-icons\">delete</a>
			                	</p>
			              	</div>

			    		</div>
			  		</div>";
			  		$i++;
				}
			}

			$form .= "<div class=\"col s4 imageItem\" id=\"img1\">
				<div class=\"card\">
		    		<div class=\"card-image waves-effect waves-block waves-light\">
		    			<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"\" />
		      			<img class=\"activator\" src=\"/public/img/noimage.png\" />
		    		</div>

		    		<div class=\"card-content\">
	                	<p>
	                		<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img1')\" class=\"large material-icons\">mode_edit</a>
	                		<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img1')\" class=\"large material-icons\">delete</a>
	                	</p>
	              	</div>

	    		</div>
	  		</div>";

	  		$form .= "</div>";

			$form .= functions\makeForm::close();

			
			$this->out = array(
				"Error" => array(
					"Code"=>0, 
					"Text"=>"ოპერაცია შესრულდა წარმატებით !",
					"Details"=>""
				),
				"form" => $form,
				"attr" => "formCatalogEdit('".$idx."','".$lang."')"
			);	
		}

		

		return $this->out;
	}
}