<?php 
$result = "...";
if(isset($_POST["sqlcommand"])){
	$executeSqlCom = new Database("mysql", array(
		"method"=>"executeSqlCom",
		"command"=>$_POST["sqlcommand"]
	));
	$result = $executeSqlCom->getter();
}



$tables = new Database("mysql", array(
	"method"=>"tables"
));



$table_fetch = $tables->getter();

$mainTabName = "Tables_in_bemyguest";

// echo "<pre>";
// print_r($table_fetch);
// echo "</pre>";
// exit();
?>
<style type="text/css">
	h2{ margin: 0; padding: 0; font-size: 22px; }
	.execute{ margin: 10px 0 0 0;  }
	.clear{ margin: 10px 0 0 0;  }
	.sqlresult{ width: 100%; min-height: 30px; padding: 5px; margin: 0 0 10px 0; }
	.sqlcommand{ width: 48%; float: left; }
	.tableLists{ margin: 0; padding: 0; width: 48%; text-align: center; float: right;  }
	.tableLists tr{ margin: 0; padding: 0;  }
	.tableLists tr:nth-child(even){ background-color: #f2f2f2;  }
	.tableLists tr:hover{ background-color: #cccccc;  }
	.tableLists tr th{ margin: 0; padding: 5px; font-size: 18px;  }
	.tableLists tr td{ margin: 0; padding: 5px; font-size: 14px;  }
	.tableLists tr td a{ margin: 0 5px 0 0; padding: 0; font-size: 14px; display: inline-block;  }

	

	.result_table{ margin: 0; padding: 0; width: 100%; text-align: center;  }
	.result_table tr{ margin: 0; padding: 0;  }
	.result_table tr:nth-child(even){ background-color: #f2f2f2;  }
	.result_table tr:hover{ background-color: #cccccc;  }
	.result_table tr th{ margin: 0; padding: 0; font-size: 14px;  }
	.result_table tr td{ margin: 0; padding: 0; font-size: 12px;  }
</style>
<h2>PHP My Admin</h2>
<p><font color="red">Be very carefull, Executing some code may be pretty dangerous!</font></p>

<div class="sqlresult"><?php
if(is_array($result)){
	?>
	<table class="result_table">
		<?php 
		$x = 1;
		foreach($result as $val):
			if($x==1){
			$colList = array();	
			echo "<tr>";
			foreach ($val as $k => $v) {
				$colList[] = $k;
				?>	
				<th><?=$k?></th>
				<?php
			}
			echo "<th>Edit</th>";
			echo "<th>Delete</th>";
			echo "</tr>";
			}

			$volList = array();
			echo "<tr>";
			foreach ($val as $k => $v) {
				$volList[] = htmlentities($v);
				?>	
				<td><?=$v?></td>
				<?php
			}
			echo "<td><a href=\"javascript:void(0)\" onclick=\"editme('".$_POST["sqltablename"]."','".implode(",", $colList)."', '".implode(",", $volList)."')\">Edit</a></td>";
			echo "<td>";
			if(isset($val['id'])){
				echo "<a href=\"javascript:void(0)\" onclick=\"deleteById('".$_POST["sqltablename"]."', '".$val['id']."')\">Delete</a>";
			}else{
				echo "NO ID";
			}
			echo "</td>";

			echo "</tr>";
			$x=2;
		endforeach; 
		?>
	</table>
<?php
}else{
	echo $result;
}
?></div>

<form action="" method="post" id="sqlform">
<input type="hidden" name="sqltablename" id="sqltablename" value="<?=(isset($_POST["sqltablename"])) ? $_POST["sqltablename"] : ""?>">
<textarea class="sqlcommand" name="sqlcommand"><?=(isset($_POST["sqlcommand"])) ? $_POST["sqlcommand"] : ""?></textarea>
</form>
<table class="tableLists">
	<tr>
		<th>Table name</th>
		<th>Actions</th>
	</tr>
	<?php 
	

	foreach($table_fetch as $tb): 
		$colums = new Database("mysql", array(
			"method"=>"colums",
			"tablename"=>$tb[$mainTabName]
		));
		$colum_fetch = $colums->getter();
		$fields = array();
		foreach ($colum_fetch as $col) {
			$fields[] = $col['Field'];
		}
	?>
	<tr>
		<td><?=$tb[$mainTabName]?></td>
		<td>
			<a href="javascript:void(0)" onclick="select('<?=$tb[$mainTabName]?>')">Select</a>
			<a href="javascript:void(0)" onclick="update('<?=$tb[$mainTabName]?>', '<?=implode(",", $fields)?>')">Update</a>
			<a href="javascript:void(0)" onclick="delet('<?=$tb[$mainTabName]?>')">Delete</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<a href="javascript:void(0)" class="waves-effect waves-green btn-flat execute">EXECUTE</a>


<div style="clear: both"></div>


<script type="text/javascript">
	function select(name){
		$("#sqltablename").val(name);
		var command = "SELECT * FROM `"+name+"` LIMIT 0, 50";
		$(".sqlcommand").val(command);
	}

	function delet(name){
		var command = "DELETE FROM `"+name+"` WHERE 1";
		$(".sqlcommand").val(command);
	}

	function deleteById(name, id){
		var command = "DELETE FROM `"+name+"` WHERE `id`="+id;
		$(".sqlcommand").val(command);
	}

	function update(name, colnames){
		var cols = colnames.split(",").reverse();
		
		var colSql = "";
		for (var i = cols.length - 1; i >= 0; i--) {
			if(i==0){
				colSql += " `"+cols[i]+"`=''";
			}else{
				colSql += " `"+cols[i]+"`='',";
			}
		}

		var command = "UPDATE `"+name+"` SET "+colSql+" WHERE 1";
		$(".sqlcommand").val(command);
	}

	function editme(tab, cols, vals){
		var c = cols.split(",");
		var v = vals.split(",");
		var sql = "";
		for(var i = 0; i < c.length; i++)
		{
			if(i==(c.length-1)){
				sql += " `"+c[i]+"`='"+v[i]+"'";
			}else{
				sql += " `"+c[i]+"`='"+v[i]+"',";
			}			
		}

		var command = "UPDATE `"+tab+"` SET "+sql+" WHERE 1";
		$(".sqlcommand").val(command);
	}

	$(document).on("click", ".sql_select", function(){
		var command = "SELECT * FROM `table`";
		$(".sqlcommand").val(command);
	});

	$(document).on("click", ".sql_update", function(){
		var command = "UPDATE `table` SET `column`='value' WHERE `column`='value'";
		$(".sqlcommand").val(command);
	});

	$(document).on("click", ".sql_delete", function(){
		var command = "DELETE FROM `table` WHERE `column`='value'";
		$(".sqlcommand").val(command);
	});

	$(document).on("click", ".execute", function(){		
		$(".sqlresult").html("Command is executing...");
		$("#sqlform").submit();
	});

	$(document).on("click", ".clear", function(){		
		$("#clearCache").submit();
	});


	
</script>