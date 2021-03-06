<?php

	session_start();
	header('content-type:text/html; charset=utf-8');
	function __autoload($class_name){
		//require_once("../../core/".$class_name.".php");
		require_once("".$class_name.".php");
	}

	$photoList = "";
	$photoCount = -1;

	try{
		$user = new YFUser('SilentImp');
		
		if(isset($_FILES['photo']['tmp_name'])){
			
			$user->authenticate('fkytnyjfy_2002');
			
			if(!move_uploaded_file($_FILES['photo']['tmp_name'], "../../photo/".$_FILES['photo']['name'])){
				throw new Exception('Немогу скопировать '.$_FILES['photo']['tmp_name'].' в '.realpath("../../photo/".basename($_FILES['photo']['name'])));
			}
			$collection = $user->addAlbumCollection("Мимикрия")->getAlbumByTitle("Тестальбом")->addPhotoCollection("Фото");
			
			if(!file_exists("../../photo/".$_FILES['photo']['name'])){
				throw new Exception("../../photo/".$_FILES['photo']['name']." не существует");
			}
			
			$photo = array(
				"path"=>"../../photo/".$_FILES['photo']['name'],
				"title"=>$_FILES['photo']['name']
			);
			$collection->addPhoto($photo);
		}
		
		$collection = $user->addAlbumCollection("Мимикрия");
		$album = $collection->getAlbumByTitle("Тестальбом");
		$photoCount = $album->getImageCount();
		$photoCollection = $album->addPhotoCollection("Фото");
		
		$i=0;
		foreach($photoCollection->getPage(0) as $photo){
			$photoList.="<img src='".$photo->getPhotoXXSUrl()."' alt='".$photo->getTitle()."' title='".$photo->getTitle()."' />";
			$i++;
		}		

	}catch(Exception $err){
		if(
			(isset($_FILES['photo']['tmp_name']))&&
			(file_exists(realpath("../../photo/".basename($_FILES['photo']['name']))))
			
		){
			unlink(realpath("../../photo/".basename($_FILES['photo']['name'])));
		}
		die($err->getMessage());
	}


$template = "
	<!DOCTYPE HTML>
	<html lang='ru-RU'>
	<head>
		<meta charset='UTF-8'>
		<title>Простой пример загрузки фото</title>
		<!--[if lt IE 9]>
			<script src='http://html5shiv.googlecode.com/svn/trunk/html5.js'></script>
		<![endif]-->
		<style type='text/css'>
			fieldset{
				border:none;
			}
			label{
				display:block;
				margin:20px 0;
			}
			button{
				margin:20px 0 0;
				display:block;		
			}
			.photoContainer img{
				margin:10px;
				float:left;
			}
			.photoContainer{
				overflow:hidden;
			}
			section{
				display:block;
				padding:20px 0 40px;
			}
		</style>
	</head>
	<body>
		<section class='photoContainer'>
			".$photoList."
		</section>
		<section>
			<p>Количество фотографий в альбоме:  ".$photoCount."</p>
			<form action='' method='post' accept='image/png,image/tiff,image/jpeg' enctype='multipart/form-data'>
				<fieldset>
					<label for='photo'>Выберите изображение: </label>
					<input type='file' name='photo' id='photo' />
					<input type='hidden' name='MAX_FILE_SIZE' value='105906176'/>
					<button>Выслать изображение</button>
				</fieldset>
			</form>
		</section>
	</body>
	</html>";


die($template);

?>