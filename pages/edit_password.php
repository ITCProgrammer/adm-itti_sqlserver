<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if($_POST){ 
	extract($_POST);
	$id = mysqli_real_escape_string($cona,$_POST['id']);
	$user = mysqli_real_escape_string($cona,$_POST['username']);
	$lama = mysqli_real_escape_string($cona,$_POST['password_lama']);
	$pass = mysqli_real_escape_string($cona,$_POST['password']);   
    $repass = mysqli_real_escape_string($cona,$_POST['re_password']); 
	if($pass!=$repass)
		{
			echo " <script>alert('Not Match Re-New Password!!');window.location='?p=Home';</script>";
			}else
			{
				$qCek=mysqli_query($cona,"SELECT * FROM tbl_user WHERE username='$user' AND password='$lama'");
				$rCek=mysqli_num_rows($qCek);
				if($rCek>0){
				$sqlupdate=mysqli_query($cona,"UPDATE `tbl_user` SET 
				`password`='$pass',
				`tgl_update`=now()
				WHERE `id`='$id' LIMIT 1");
				echo " <script>window.location='?p=Home';</script>";
				}else{
				echo " <script>alert('Wrong Password!!');window.location='?p=Home';</script>";
			}
		}
		
}
?>
