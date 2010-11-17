<div class="content">	
	<div class="content-header">
		<h4><a href="?p=admin">Main Menu</a> / Send Email</h4>
	</div> <!-- .content-header -->				
	<div class="main-content">	
	<?php 
		if(isset($_POST['send_email'])) 
		{
			send_email($_POST['reciever'],'wilson212',$_POST['subject'],$_POST['message']);
		}
	?>			
		<form method="POST" action="?p=admin&sub=email" class="form label-inline">
		<input type="hidden" name="send_email">
		
			<table>
				<thead>
					<tr>
						<th><center>Send Email</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Subject">Subject: </label>
				<input id="Subject" name="subject" size="20" type="text" class="medium" />
				<p class="field_help">Enter your email subject here.</p>
			</div>
			
			<div class="field">
				<label for="Subject">Send To: </label>
				<input id="Subject" name="reciever" size="20" type="text" class="medium" />
				<p class="field_help">Enter an email address</p>
			</div>
			
			<div class="field">
				<textarea id="Message" name="message" rows="15" cols="78" class="inputbox"></textarea>
			</div>
			<br />		
			<div class="buttonrow-border">								
				<center><button><span>Send Email</span></button></center>			
			</div>
		</form>
	</div>
</div>