<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if($_POST){ 
	extract($_POST);
	$id = mysqli_real_escape_string($con,$_POST['id']);
	$nama = mysqli_real_escape_string($con,$_POST['nama']); 
	$user = mysqli_real_escape_string($con,$_POST['username']); 
	$pass = mysqli_real_escape_string($con,$_POST['password']);   
    $repass = mysqli_real_escape_string($con,$_POST['re_password']); 
    $level = mysqli_real_escape_string($con,$_POST['level']);
    $status = mysqli_real_escape_string($con,$_POST['status']);
$datauser=mysqli_query($con,"SELECT count(*) as jml FROM tbl_user WHERE `username`='$user' LIMIT 1");
$row=mysqli_fetch_array($datauser);
	if($row['jml']>0){
		echo " <script>alert('Someone already has this username!');window.location='?p=User';</script>";
	}
	else if($pass!=$repass)
		{
			echo " <script>alert('Not Match Re-New Password!');window.location='?p=User';</script>";
			}else
			{
				$sqlupdate=mysqli_query($con,"INSERT INTO `tbl_user` SET 
				`nama`='$nama',
				`username`='$user', 
				`password`='$pass',
				`level`='$level',
				`status`='$status',
				`foto`='avatar',
				`dept`='DYE',
				`tgl_update`=now()
				");
				echo " <script>window.location='?p=User';</script>";
				}
		
		}
		

?>