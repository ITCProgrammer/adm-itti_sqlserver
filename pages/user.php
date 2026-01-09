<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";

?>
<?php
//set base constant
if ($_SESSION['lvl_id10']!="1") {
	echo 'Illegal Acces';
}else{
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>user</title>
</head>

<body>
<?php
   $datauser=mysqli_query($con,"SELECT * FROM tbl_user WHERE dept='DYE' ORDER BY username ASC");
	$no=1;
	$n=1;
	$c=0;
	 ?>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
<div class="box-header">
  <a href="#" data-toggle="modal" data-target="#DataUser" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add</a>
      </div>
      <div class="box-body">	 
<table width="100%" class="table table-bordered table-hover display nowrap">
 <thead class="btn-primary">
  <tr>
    <th width="5%"><div align="center">No</div></th>
    <th width="57%"><div align="center">Nama</div></th>
    <th width="57%"><div align="center">UserName</div></th>
    <th width="15%"><div align="center">Level</div></th>
    <th width="13%"><div align="center">Status</div></th>
    <th width="10%"><div align="center">Action</div></th>
  </tr>
  </thead>
  <tbody>
  <?php
	  $col=0;
  while($rowd=mysqli_fetch_array($datauser)){ 
			$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
		 ?> 
  <tr align="center" bgcolor="<?php echo $bgcolor; ?>">
    <td><?php echo $no;?></td>
    <td><?php echo $rowd['nama'];?></td>
    <td><?php echo $rowd['username'];?></td>
    <td><?php if($rowd['level']=='1'){echo"SuperAdmin";}else if($rowd['level']=='2'){echo"Admin";}else{echo"Biasa";} ;?></td>
    <td><?php echo $rowd['status'];?></td>
    <td><a href="#" id='<?php echo $rowd['id'] ?>' class="btn btn-info user_edit"><i class="fa fa-edit"></i> </a></td>
  </tr>
  <?php 
  $no++;} ?>
  </tbody>
</table>
<div class="modal fade" id="DataUser">
          <div class="modal-dialog ">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=simpan_user" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Data User</h4>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="id" name="id">
                  <div class="form-group">
                  <label for="nama" class="col-md-3 control-label">Nama</label>
                  <div class="col-md-6">
                  <input type="text" class="form-control" id="nama" name="nama" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
				  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Username</label>
                  <div class="col-md-6">
                  <input type="text" class="form-control" id="username" name="username" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Password</label>
                  <div class="col-md-6">
                  <input type="password" class="form-control" id="nama" name="password" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>	
                  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Re-Password</label>
                  <div class="col-md-6">
                  <input type="password" class="form-control" id="nama" name="re_password" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>	
                  <div class="form-group">
                  <label for="level" class="col-md-3 control-label">Level</label>
                  <div class="col-md-6">
                  <select name="level" class="form-control" id="level" required>
                  	<option value="1">SuperAdmin</option>
                  	<option value="2">Admin</option>
                  	<option value="3">Biasa</option>
                  </select>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>                  
                <div class="form-group">
                  <label for="status" class="col-md-3 control-label">Status</label>
                  <div class="col-md-6">
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" value="Aktif" id="status_0" checked>
                      Aktif
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" value="Non-Aktif" id="status_1">
                      Non-Aktif
                    </label>
                  </div>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>			    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" >Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
</div>
<!-- Modal Popup untuk Edit--> 
<div id="UserEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
</body>
</html>
	  
	  <?php } ?>