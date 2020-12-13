<?php

/**
 * 
 */
class Upload {
	
	public function uploadfile($file) {
		$result = array();
		$target_dir = dirname(__FILE__)."/uploads/";
		$target_file = $target_dir . basename($file["name"]);
		$uploadOk = 1;
		$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Allow certain file formats
		if($fileType != "xlsx") {
			$result["error"] = "Sorry, only XLSX file is allowed.";
			$uploadOk = 0;
		}

		// Check if file already exists
		$i = 0;
		while (file_exists($target_file)) {
			$i ++;
			$target_file = $target_dir . $i . basename($file["name"]);
		}

		// Check file size
		if ($file["size"] > 500000) {
			$result["error"] = "Sorry, your file is too large.";
			$uploadOk = 0;
		}


		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$result["error"] = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($file["tmp_name"], $target_file)) {
				$result["success"] = $target_file;
			} else {
				$result["error"] = "Sorry, there was an error uploading your file.";
			}
		}

		return $result;
	}
}

?>
