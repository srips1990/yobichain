<?php 
	include_once("page_header.php");
	include_once("primechain_functions/crud_engine.php");
	$crud_engine = new crudEngine();
?>


<div class="row">
	<div class="col-lg-12">
		<form role="form" action="password_change_process.php" method="post">
			
			<div class="alert alert-info">  
				<div class="form-group">
					<label>1. Your current password *</label>
					<input type="password" name="old_password" class="form-control" required>
					<p class="help-block">Please enter your current password.</p>
				</div>
			</div>

			<div class="alert alert-info">  
				<div class="form-group">
					<label>2. Your new password *</label>
					<input type="password" name="new_password_1" class="form-control" required>
					<p class="help-block">Strong passwords = Happy computers.</p>
				</div>
			</div>

			<div class="alert alert-info">  
				<div class="form-group">
					<label>3. Your new password *</label>
					<input type="password" name="new_password_2" class="form-control" required>
					<p class="help-block">Re-enter your new password</p>
				</div>
			</div>
			
			<button type="submit" class="btn btn-primary">Change password </button>
			<button type="reset" class="btn btn-danger">Reset</button>
			
		</form>
	</div>
</div>

<?php include("page_footer.php");?>