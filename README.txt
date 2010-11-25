-- 1.1 Full Install --

	1.1a Requirements:
	
	Apache with Mysql & PhP support
	- Apache v2.2 or higher
	- MySQL 5 or higher
	- Php version 5.2 or higher
	- GD compiled into Php (In windows, enable GD exetension in php.ini file).

	1.1b Installing The Site
	
	1. Make sure all files are in the same folder under you "htdocs" or "www" folder
	2. Enter your site url in your Internet Browswer (Ex: http://yourdomain.com)
	3. You will be automatically redirected to the installer.
	4. Just follow the on screen instructions.
	5. On step 2, if you arent able to use mangosweb, you will see the reason why.
	6. Once completed, you need to edit line 3 of the installer. change "$disabled = FALSE;" to "$disabled = TRUE;"
	

	1.1c How To Update
	1. Go to your Admin Control Panel and click "Check For Updates" on the last row.
	2. If there are any updates, it will show you a list of files that will be updated. Click "Update MangosWeb" to begin the update process.
	3. The update process is automatic and will end in just a few seconds. Once done click "Return"
	4. Continue the process untill there are no more updates. Its that easy.


-- 1.2 Upgrading From older versions of MangosWeb --
As of right now, it is impossible to use your old MangosWeb Enahnced tables. Because of this You will need to do a fresh install of v3.