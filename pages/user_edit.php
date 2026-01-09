<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$modal=mysqli_query($con,"SELECT * FROM `tbl_user` WHERE id='$modal_id' ");
while($r=mysqli_fetch_array($modal)){
?>
          <div class="modal-dialog ">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_user" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit User</h4>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">
                  <div class="form-group">
                  <label for="nama" class="col-md-3 control-label">Nama</label>
                  <div class="col-md-6">
                  <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $r['nama'];?>"  required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
				  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Username</label>
                  <div class="col-md-6">
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo $r['username'];?>"  required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Password</label>
                  <div class="col-md-6">
                  <input type="password" class="form-control" id="nama" name="password" value="<?php echo $r['password'];?>" required>
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
                  	<option value="1" <?php if($r['level']=="1"){echo "SELECTED";}?>>SuperAdmin</option>
          <option value="2" <?php if($r['level']=="2"){echo "SELECTED";}?>>Admin</option>
          <option value="3" <?php if($r['level']=="3"){echo "SELECTED";}?>>Biasa</option>
                  </select>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="username" class="col-md-3 control-label">Status</label>
                  <div class="col-md-6">
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" value="Aktif" id="status_0" <?php if($r['status']=="Aktif"){echo "checked";}?>>
                      Aktif
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" value="Non-Aktif" id="status_1" <?php if($r['status']=="Non-Aktif"){echo "checked";}?>>
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
          <?php } ?>
