<div class="content">	
	<div class="content-header">
		<h4><a href="?p=admin">Main Menu</a> / Database Config</h4>
	</div> <!-- .content-header -->	
	<div class="main-content">
		<?php 
			if(isset($_POST['task'])) 
			{
				saveConfig();
			} 
		?>
		<form method="POST" action="?p=admin&sub=dbconfig" name="adminform" class="form label-inline">
		<input type="hidden" name="task" value="saveconfig">
			
			<table>
				<thead>
					<tr>
						<th><center>Database Config</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="dbh">Database Host: </label>
				<input id="dbh" name="db_host" size="20" type="text" class="medium" value="<?php echo $Config->getDbInfo('db_host'); ?>" />
				<p class="field_help">Enter your database host address here.</p>
			</div>
			
			<div class="field">
				<label for="dbh">Database Port: </label>
				<input id="dbh" name="db_port" size="20" type="text" class="medium" value="<?php echo $Config->getDbInfo('db_port'); ?>" />
				<p class="field_help">Enter your database port numberr here. (default 3306)</p>
			</div>
			
			<div class="field">
				<label for="dbh">Database User: </label>
				<input id="dbh" name="db_username" size="20" type="text" class="medium" value="<?php echo $Config->getDbInfo('db_username'); ?>" />
				<p class="field_help">Enter your database username here.</p>
			</div>
			
			<div class="field">
				<label for="dbh">Database Pass: </label>
				<input id="dbh" name="db_password" size="20" type="text" class="medium" value="<?php echo $Config->getDbInfo('db_password'); ?>" />
				<p class="field_help">Enter your database password here.</p>
			</div>
			
			<div class="field">
				<label for="dbh">Database Name: </label>
				<input id="dbh" name="db_name" size="20" type="text" class="medium" value="<?php echo $Config->getDbInfo('db_name'); ?>" />
				<p class="field_help">Enter your realm database name here.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Update Config</span></button></center>			
			</div>
		</form>
	</div>
</div> <!-- .content -->	