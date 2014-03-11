<?php

	if(isset($_FILES['pic']))
				{
					$allowed=array('image/jpeg', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/jpg');
					if(in_array($_FILES['pic']['type'], $allowed))
					{
						if(!is_dir('/gaming/uploads/games/' .$_POST['colID']. '/'))
						{
							mkdir('/gaming/uploads/games/' .$_POST['colID']. '/');
						}
						$imageName=uniqid(). '.' .  pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
						if(move_uploaded_file($_FILES['pic']['tmp_name'], '/gaming/uploads/games/'.$_POST['colID']. '/' .$imageName))
						{
							echo "<div class='alert alert-success'>
									<p>Image has been uploaded</p>
									</div>";
						}

					}else{
						$errors[]='Sorry, wrong file type. Please use JPG, PNG or GIF';
					}
				}

			if($_FILES['pic']['error'] > 0)
				{
					switch($_FILES['image']['error'])
					{
						case 1: 
							$errors[]='File exceeds Max File Size';
							break;
						case 2:
							$errors[]='File exceeds HTML Max file Size';
							break;

						case 3:
							$errors[]='File was only partially uploaded';
							break;

						case 4:
							$errors[]='No file was uploaded';
							break;

						case 6:
							$errors[]='No temp folder was available';
							break;

						case 7:
							$errors[]='unable to write to disk';
							break;

						case 8:
							$errors[]='File upload stopped';
							break;

						default:
							$errors[]='System connection was lost';
							break;
					}

				}
			if(file_exists($_FILES['image']['tmp_name']) && isfile($_FILES['upload']['tmp_name']))
				{
					unlink($_FILES['upload']['tmp_name']);
				}

?>
<div class='form-group'>
			<label for='pic'>Group Icon</label>
			<input type='hidden' name='MAX_FILE_SIZE' value='524288'>
			<input type='file' name='pic'>
			<p class='help-block'>Try to keep the image to either square or 3:2 ratio</p>
		</div>