<div class="content">	
	<div class="content-header">
		<h4><a href="index.php?p=admin">Main Menu</a> / Register Keys</h4>
	</div> <!-- .content-header -->	
	<div class="main-content">
		<table style="border-bottom: 1px solid #E5E2E2;">
			<thead>
				<th><i>Description</i></th>
			</thead>
			<tr>
				<td>
					Here you can create and delete Register keys to your website. If you are unfimiliar with the key register system, these are basically Invite
					Keys. If you have your server set to "Invite Only", then a guest cannot create an account with out one of these keys. YOU the admin must
					hand these keys out, users cannot hand them out.
				</td>
			</tr>
		</table>
		<br />
		
		<?php
			if(isset($_GET['action']))
			{
				if($_GET['action'] == 'create')
				{
					createKeys();
				}
				elseif($_GET['action'] == 'delete')
				{
					deleteKey();
				}
				elseif($_GET['action'] == 'setused')
				{
					setUsed();
				}
				elseif($_GET['action'] == 'deleteall')
				{
					$DB->query("TRUNCATE TABLE mw_regkeys");
				}
			}
		$allkeys = $DB->select("SELECT * FROM `mw_regkeys`");
		$num_keys = $DB->count("SELECT COUNT(*) FROM mw_regkeys");
		?>
		
		<p>
			<a href="index.php?p=admin&sub=regkeys&action=deleteall" onclick="return confirm('Are you sure?');"><b>[ <font color="red">Delete all keys</font> ]</b></a><br/>
			<form method="post" action="index.php?p=admin&sub=regkeys&action=create" class="form label-inline">
				Enter number of keys desired (1-300): <input type="text" name="num" size="4"> 
				&nbsp; &nbsp; <button><span>Create</span></button>
			</form>
		</p>
		<ul style="font-weight:bold;list-style:none;">
			<?php
				if($allkeys != FALSE)
				{
					foreach($allkeys as $key)
					{
						if($key['used'] == 0)
						{
							echo'<li><a href="index.php?p=admin&sub=regkeys&action=delete&keyid='.$key['id'].'" title="Delete">[Delete]</a>
								&nbsp; '.$key['id'].') <a href="index.php?p=admin&sub=regkeys&action=setused&keyid='.$key['id'].'" title="Mark as used">'.$key['key'].'</a></li>'."\n";
						}
						else 
						{
							echo'<li><a href="index.php?p=admin&sub=regkeys&action=delete&keyid='.$key['id'].'" title="Delete">[Delete]</a>
							&nbsp; <s>'.$key['id'].') '.$key['key'].'</s></li>'."\n";
						}
					} 
				}
			?>
		</ul>	
	</div>
</div>