<?php
// If Project Is Trinity
if($project == "trinity") {
$result11 = $DB->select("SELECT username FROM account WHERE id in(SELECT id from account_access where gmlevel=3) ORDER BY username");
$result21 = $DB->select("SELECT username FROM account WHERE id in(SELECT id from account_access WHERE gmlevel=2) ORDER BY username");
$result31 = $DB->select("SELECT username FROM account WHERE id in(SELECT id from account_access WHERE gmlevel=1) ORDER BY username");
?>
<img src="templates/WotLK/images/gms.jpg" border="0" width="659" /> 
<br> 
<br> 
<div style="width: 659px; height: 29px; background: url('templates/WotLK/images/content-parting2.jpg') no-repeat;"><div style="padding: 2px 0px 0px 23px;"><font style="font-family: 'Times New Roman', Times, serif; color: #640909;"><h2>Administrators</h2></font></div></div><div style="background: url('templates/WotLK/images/light.jpg') repeat; border-width: 1px; border-color: #000000; border-bottom-style: solid; margin: 0px 0px 5px 0px"><div class="contentdiv"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="postContainerPlain"	> 
  <tr> 
    <td width="20%"><div align="center"><img src="templates/WotLK/images/GM-gnome.gif" width="86" height="133" /></div></td> 
    <td width="80%"><div style="margin-right: 0pt;"  align="left"> 
      <div class="postBody" style="list-style:square;">
      <table width="500" cellspacing="20">
      <tr>
      <td>
      <table>
	  <?php
foreach($result11 as $row)
  {	  
	  echo "<tr>";
	  echo "<td><li>" . $row['username'] . "</li></td>";
  	  echo "</tr>";
	    }
  ?>
  </table>
  </td>
  </tr>
  </table>
              </div> 
    </div></td> 
  </tr> 
</table> 
</div></div><br> 
<div style="width: 659px; height: 29px; background: url('templates/WotLK/images/content-parting2.jpg') no-repeat;"><div style="padding: 2px 0px 0px 23px;"><font style="font-family: 'Times New Roman', Times, serif; color: #640909;"><h2>Game Masters</h2></font></div></div><div style="background: url('templates/WotLK/images/light.jpg') repeat; border-width: 1px; border-color: #000000; border-bottom-style: solid; margin: 0px 0px 5px 0px"><div class="contentdiv"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="postContainerPlain"	> 
  <tr> 
    <td width="20%"><div align="center"><img src="templates/WotLK/images/GM-gnome.gif" width="86" height="133" /></div></td> 
    <td width="80%"><div style="margin-right: 0pt;"  align="left"> 
      <div class="postBody" style="list-style:square;"> 
                <table width="500" cellspacing="20">
      <tr>
      <td>
      <table>
	  <?php
foreach($result21 as $row)
  {	  
	  echo "<tr>";
	  echo "<td><li>" . $row['username'] . "</li></td>";
  	  echo "</tr>";
	    }
  ?>
  </table>
  </td>
  </tr>
  </table>
              </div> 
    </div></td> 
  </tr> 
</table> 
</div></div><br> 
<div style="width: 659px; height: 29px; background: url('templates/WotLK/images/content-parting2.jpg') no-repeat;"><div style="padding: 2px 0px 0px 23px;"><font style="font-family: 'Times New Roman', Times, serif; color: #640909;"><h2>Moderators</h2></font></div></div><div style="background: url('templates/WotLK/images/light.jpg') repeat; border-width: 1px; border-color: #000000; border-bottom-style: solid; margin: 0px 0px 5px 0px"><div class="contentdiv"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="postContainerPlain"	> 
  <tr> 
    <td width="20%"><div align="center"><img src="templates/WotLK/images/GM-gnome.gif" width="86" height="133" /></div></td> 
    <td width="80%"><div style="margin-right: 0pt;"  align="left"> 
      <div class="postBody" style="list-style:square;"> 
                <table width="500" cellspacing="20">
      <tr>
      <td>
      <table>
	  <?php
foreach($result31 as $row)
  {	  
	  echo "<tr>";
	  echo "<td><li>" . $row['username'] . "</li></td>";
  	  echo "</tr>";
	    }
  ?>
  </table>
  </td>
  </tr>
  </table>
              </div> 
    </div></td> 
  </tr> 
</table> 
</div></div> 

<!-- If Project Is Mangos -->   
<?php }elseif($project == "mangos") { ?>
<img src="<?php echo $currtmp; ?>/images/gms.jpg" border="0" width="659" />
<br>
<?php foreach($gm_groups as $gm_group_id=>$gm_group){ ?>
<br>
<?php builddiv_start(1, $gmlevel_w[$gm_group_id]) ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="postContainerPlain"	>
  <tr>
    <td width="20%"><div align="center"><img src="<?php echo $currtmp; ?>/images/GM-gnome.gif" width="86" height="133" /></div></td>
    <td width="80%"><div style="margin-right: 0pt;"  align="left">
      <div class="postBody" style="list-style:square;">
        <?php foreach($gm_group as $gm_name){ ?>
        <li>Account ID: <?php echo $gm_name;?></li>
        <?php } ?>
      </div>
    </div></td>
  </tr>
</table>
<?php builddiv_end() ?>
<?php 
	}
}else{
echo "Your current emulator \"" . $project . "\", isn't supported yet";
exit;
}
 ?>