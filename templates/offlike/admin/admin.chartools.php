<br />
<?php builddiv_start(1, "Admin Character Rename System") ?>
Here you can change character names on your server. Please take note that members can change there characters name as well through there account managment!<br /><br />
<center>
<?php write_subheader("Character Re-name"); ?>
<table width = "580" style = "border-width: 1px; border-style: dotted; border-color: #928058;"><tr><td><table style = "border-width: 1px; border-style: solid; border-color: black; background-image: url('<?php echo $currtmp; ?>/images/light3.jpg');"><tr><td>

<table border=0 cellspacing=0 cellpadding=4 width="580px">
<tr>
<td>
<form action="index.php?n=admin&sub=chartools" method="post">
<center>
<br />
<table width="300" border="0" cellpadding="2px">
  <tr>
  <td><?php echo $lang['charname']; ?></td>
  <td><input type='text' name='name' maxlength='20' size='20'/>
  </td>
  </tr>
  <tr>
    <td><?php echo $lang['desired_name']; ?></td>
    <td><input type='text' name='newname' maxlength='20' size='20'/></td>
  </tr>  
</table>
<input type='submit' name='rename' value='Rename'  />
</center>
</form>
<?php
if (isset($_POST['rename'])) {
    if (($_POST['name']) == '' or ($_POST['newname']) == '') {
        echo "<p align='center'><font color='red'>Please enter a New Name</font></p>";
            exit();
        }
        $name = $_POST['name'];
        $newname = ucfirst(strtolower(trim($_POST['newname'])));
        $status = check_if_online($name, $CHDB);
        $newname_exist = check_if_name_exist($newname, $CHDB);
        if ($status == -1) {
            echo "<p align='center'><font color='red'>The character doesnt exsist
                </font></p>";
            exit();
        }
        if ($newname_exist == 1) {
            echo "<p align='center'><font color='red'>The character already exsists, please choose a different name!</font></p>";
            exit();
        }
    if ($status == 1)
        echo "<p align='center'><font color='red'>This character is online. Please try again later</font></p>";
    else {
        change_name($name, $newname, $CHDB, $DB);
        echo "<p align='center'><font color='blue'>Success! Character " .$name." renamed to " .$newname. "</font></p>";
    }
}
?>
</td>
</tr>
</table>
</td></tr></table>
</td></tr></table>
</center>
<?php builddiv_end() ?>