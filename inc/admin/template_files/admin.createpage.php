<div class="content">	
	<div class="content-header">
		<h4><a href="index.php?p=admin">Main Menu</a> / Create Page</h4>
	</div> <!-- .content-header -->	
	<div class="main-content">
		<?php 
		if(isset($_POST['add_page'])) 
		{
			buildPage();
		} ?>
		<form method="POST" action="index.php?p=admin&sub=createpage" name="adminform" class="form label-inline">
		<input type="hidden" name="add_page" value="add">	
		<table>
			<thead>
					<th><center>Create A New Page</center></th>
			</thead>
		</table>
		<br />
		
		<div class="field">
			<label for="dbh">Page Title: </label>
			<input id="dbh" name="page_title" size="20" type="text" class="medium" />
			<p class="field_help">The title of your page.</p>
		</div>
		
		<div class="field">
			<label for="dbh">Page Extension: </label>
			<input id="dbh" name="page_ext" size="20" type="text" class="medium" />
			<p class="field_help">Extension (ie: "server", "vote", "account").</p>
		</div>
		
		<div class="field">
			<label for="dbh">Page Sub-Extension: </label>
			<input id="dbh" name="page_sub" size="20" type="text" class="medium" value="index"/>
			<p class="field_help">The sub-ext is the "sub" you see in the address bar. It basically a sub catagory for the main extension.</p>
		</div>
		
		<div class="field">
			<label for="vl">Viewing Level: </label>
			<select id="type" class="small" name="page_level">
				<option value="1">Guest</option>
				<option value="2">Members</option>
				<option value="3">Admins</option>
				<option value="4">Super Admins</option>
			</select>
			<p class="field_help">What min. account level does the user need to view this page?</p>
		</div>

		<div class="buttonrow-border">								
			<center><button><span>Create</span></button></center>			
		</div>
		</form>
	</div>
</div>