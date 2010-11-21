<?php
$templategenderimage = array(
    0 => $Template['path'].'/images/pixel.gif',
    1 => $Template['path'].'/images/icons/male.gif',
    2 => $Template['path'].'/images/icons/female.gif'
);

function population_view($n) 
{
    global $lang;
    $maxlow = 100;
    $maxmedium = 200;
    if($n <= $maxlow){
        return '<font color="green">' . $lang['low'] . '</font>';
    }elseif($n > $maxlow && $n <= $maxmedium){
        return '<font color="orange">' . $lang['medium'] . '</font>';
    }else{
        return '<font color="red">' . $lang['high'] . '</font>';
    }
}

function build_menu_items($links_arr)
{
    global $user;
    global $lang;
    $r = "\n";
    foreach($links_arr as $menu_item)
	{
        $ignore_item = 0;
        if($menu_item['link_title'] | $menu_item['link']) 
		{
            if($menu_item['account_level'] > $user['account_level']) 
			{
                $ignore_item++;
            }
			if($menu_item['guest_only'] == 1 && $user['id'] > 0)
			{
			    $ignore_item++;
            }
        }
        if($ignore_item == 0)
		{
            $r .= '<div><a class="menufiller" href="'.$menu_item['link'].'">'.$menu_item['link_title'].'</a></div>'."\n";
		}
    }
    return $r;
}

function build_main_menu()
{
	global $DB, $user, $Core;
    $mainnav_links = array(
		'1-menuNews', 
		'2-menuAccount', 
		'3-menuGameGuide', 
		'4-menuInteractive', 
		'5-menuMedia', 
		'6-menuForums', 
		'7-menuCommunity',
		'8-menuSupport'
		);
    foreach($mainnav_links as $menuname)
	{
        $menunamev = explode('-',strtolower($menuname));
		if($user['id'] > 0)
		{
			$menuquery = "SELECT * FROM `mw_menu_items` WHERE `menu_id`='$menunamev[0]' AND `account_level` <= '$user[account_level]' AND `guest_only` != 1 ORDER BY `order` ASC";
		}
		else
		{
			$menuquery = "SELECT * FROM `mw_menu_items` WHERE `menu_id`='$menunamev[0]' AND `account_level` <= '$user[account_level]' ORDER BY `order` ASC";
		}
		$menuitems = $DB->select($menuquery);
        if($menuitems != FALSE)// && $menuitems[0][0])
        {
            static $index = 0;
            $index++;
			echo '
                <div id="'.$menunamev[1].'"  style="position: relative; z-index: 11;"> 
					<div onclick="javascript:toggleNewMenu('.$menunamev[0].'-1);" class="menu-button-off" id="'.$menunamev[1].'-button">
						<span class="'.$menunamev[1].'-icon-off" id="'.$menunamev[1].'-icon">&nbsp;</span><a class="'.$menunamev[1].'-header-off" id="'.$menunamev[1].'-header"><em>Menu item</em></a><a id="'.$menunamev[1].'-collapse"></a><span class="menuentry-rightborder"></span>
                    </div>
                    <div id="'.$menunamev[1].'-inner">
                        <script type="text/javascript">
                        if (menuCookie['.$menunamev[0].'-1] == 0) 
						{
                            document.getElementById("'.$menunamev[1].'-inner").style.display = "none";
                            document.getElementById("'.$menunamev[1].'-button").className = "menu-button-off";
                            document.getElementById("'.$menunamev[1].'-collapse").className = "leftmenu-pluslink";
                            document.getElementById("'.$menunamev[1].'-icon").className = "'.$menunamev[1].'-icon-off";
                            document.getElementById("'.$menunamev[1].'-header").className = "'.$menunamev[1].'-header-off";
                        } 
						else
						{
                            document.getElementById("'.$menunamev[1].'-inner").style.display = "block";
                            document.getElementById("'.$menunamev[1].'-button").className = "menu-button-on";
                            document.getElementById("'.$menunamev[1].'-collapse").className = "leftmenu-minuslink";
                            document.getElementById("'.$menunamev[1].'-icon").className = "'.$menunamev[1].'-icon-on";
                            document.getElementById("'.$menunamev[1].'-header").className = "'.$menunamev[1].'-header-on";
                        }
                        </script>
                        <div class="leftmenu-cont-top"></div>
                        <div class="leftmenu-cont-mid">
                            <div class="m-left">
                                <div class="m-right">
                                    <div class="leftmenu-cnt" id="menucontainer'.$index.'">
                                        <ul class="mainnav">
                                            <li style="position:relative;" id="menufiller'.$index.'">
                                                '.build_menu_items($menuitems).'
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="leftmenu-cont-bot"></div>
                    </div>
                </div>
			';
        }
    }
	unset($menuquery);
}

function write_subheader($subheader)
{
	global $Template;
    echo '
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td width="24"><img src="'.$Template['path'].'/images/subheader/subheader-left-sword.gif" height="20" width="24" alt=""/></td>
				<td bgcolor="#05374a" width="100%"><b style="color:white;">'.$subheader.':</b></td>
				<td width="10"><img src="'.$Template['path'].'/images/subheader/subheader-right.gif" height="20" width="10" alt=""/></td>
			</tr>
		</tbody>
	</table>';
}
function write_metalborder_header()
{
	global $Template;
    echo '
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td width="12"><img src="'.$Template['path'].'/images/metalborder-top-left.gif" height="12" width="12" alt=""/></td>
				<td style="background:url(\''.$Template['path'].'/images/metalborder-top.gif\');"></td>
				<td width="12"><img src="'.$Template['path'].'/images/metalborder-top-right.gif" height="12" width="12" alt=""/></td>
			</tr>
			<tr>
				<td style="background:url(\''.$Template['path'].'/images/metalborder-left.gif\');"></td>
				<td>
';
}

function write_metalborder_footer()
{
	global $Template;
	echo '      </td>
				<td style="background:url(\''.$Template['path'].'/images/metalborder-right.gif\');"></td>
			</tr>
			<tr>
				<td><img src="'.$Template['path'].'/images/metalborder-bot-left.gif" height="11" width="12" alt=""/></td>
				<td style="background:url(\''.$Template['path'].'/images/metalborder-bot.gif\');"></td>
				<td><img src="'.$Template['path'].'/images/metalborder-bot-right.gif" height="11" width="12" alt=""/></td>
			</tr>
		</tbody>
	</table>
';
}

function build_CommBox_Header()
{
	global $Template;
	echo "<br />
	<table align='center' width='60%' style='font-size:0.8em;'>
	<tr>
		<td align='left'>
			<div id='container-community'>
				<div class='phatlootbox-top'>
					<h2 class='community'><span class='hide'>Registration</span></h2>
					<span class='phatlootbox-visual comm'></span>
					</div>
					<div class='phatlootbox-wrapper'>
						<div style='background: url(".$Template['path']."/images/phatlootbox-top-parchment.jpg) repeat-y top right; height: 7px; width: 456px; margin-left: 6px; font-size: 1px;'></div>
						<div class='community-cnt'>
	";
}

function build_CommBox_Footer()
{
	echo "
					<br/>
				</div>
			</div>
		<div class='phatlootbox-bottom'></div>
		</div>
	</td>
	</tr>
	</table>
	";
}

function write_form_tool()
{
	global $Template;
    $template_href = $Template['path'] . "/";
?>
        <div id="form_tool">
            <ul id="bbcode_tool">
                <li id="bbcode_b"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-bold.gif" alt="<?php lang('editor_bold'); ?>" title="<?php lang('editor_bold'); ?>"></a></li>
                <li id="bbcode_i"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-italic.gif" alt="<?php lang('editor_italic'); ?>" title="<?php lang('editor_italic'); ?>"></a></li>
                <li id="bbcode_u"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-underline.gif" alt="<?php lang('editor_underline'); ?>" title="<?php lang('editor_underline'); ?>"></a></li>
                <li id="bbcode_url"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-url.gif" alt="<?php lang('editor_link'); ?>" title="<?php lang('editor_link'); ?>"></a></li>
                <li id="bbcode_img"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-img.gif" alt="<?php lang('editor_image'); ?>" title="<?php lang('editor_image'); ?>"></a></li>
                <li id="bbcode_blockquote"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-quote.gif" alt="<?php lang('editor_quote'); ?>" title="<?php lang('editor_quote'); ?>"></a></li>
            </ul>
            <ul id="text_tool">
                <li id="text_size"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-size.gif" alt="<?php lang('editor_size'); ?>" title="<?php lang('editor_size'); ?>"></a>
                    <ul>
                        <li id="text_size-hugesize"><a href="#">Huge</a></li>
                        <li id="text_size-largesize"><a href="#">Large</a></li>
                        <li id="text_size-mediumsize"><a href="#">Medium</a></li>
                    </ul>
                </li>
                <li id="text_color"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-color.gif" alt="<?php lang('editor_color'); ?>" title="<?php lang('editor_color'); ?>"></a>
                    <ul>
                        <li id="text_color-red"><a href="#"><?php lang('editor_color_red'); ?></a></li>
                        <li id="text_color-green"><a href="#"><?php lang('editor_color_green'); ?></a></li>
                        <li id="text_color-blue"><a href="#"><?php lang('editor_color_blue'); ?></a></li>
                        <li id="text_color-custom"><a href="#"><?php lang('editor_color_custom'); ?></a></li>
                    </ul>
                </li>
                <li id="text_align"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-list.gif" alt="<?php lang('editor_align'); ?>" title="<?php lang('editor_align'); ?>"></a>
                    <ul>
                        <li id="text_align-left"><a href="#"><?php lang('editor_align_left'); ?></a></li>
                        <li id="text_align-right"><a href="#"><?php lang('editor_align_right'); ?></a></li>
                        <li id="text_align-center"><a href="#"><?php lang('editor_align_center'); ?></a></li>
                        <li id="text_align-justify"><a href="#"><?php lang('editor_align_justify'); ?></a></li>
                    </ul>
                </li>
                <li id="text_smile"><a href="#"><img src="<?php echo $template_href;?>editor/images/button-emote.gif" alt="<?php lang('editor_smile'); ?>" title="<?php lang('editor_smile'); ?>"></a>
                    <ul>
<?php
$smiles = load_smiles();
$smilepath = "images/smiles/";
foreach($smiles as $smile):
    $smilename = ucfirst(str_replace('.gif','',str_replace('.png','',$smile)));
?>
                        <li id="text_smile-<?php echo $smilepath.$smile;?>"><a href="#" title="<?php echo $smilename;?>"><img src="<?php echo $smilepath.$smile;?>" alt="<?php echo $smilename;?>"></a></li>
<?php
endforeach;
?>
                    </ul>
                </li>
            </ul>
        </div>
<?php
}

function random_screenshot()
{
	$fa = array();
	if ($handle = opendir('images/screenshots/thumbs/')) 
	{
		while (false !== ($file = readdir($handle))) 
		{
			if ($file != "." && $file != ".." && $file != "Thumbs.db" && $file != "index.html") 
			{
				$fa[] = $file;
			}
		}
		closedir($handle);
	}
	$fnum = count($fa);
	$fpos = rand(0, $fnum-1);
	return $fa[$fpos];
}

function build_pathway()
{
    global $lang;
    global $pathway_info;
    global $title_str,$pathway_str;
    $path_c = count($pathway_info);
    $pathway_info[$path_c-1]['link'] = '';
    $pathway_str = '';
    if(empty($_REQUEST['p']) || !is_array($pathway_info))
	{
		$pathway_str .= ' <b><u>Main</u></b>';
	}
    else
	{
		$pathway_str .= '<a href="./">Main</a>';
	}
    if(is_array($pathway_info))
	{
        foreach($pathway_info as $newpath)
		{
            if(isset($newpath['title']))
			{
                if(empty($newpath['link']))
				{
					$pathway_str .= ' &raquo; '.$newpath['title'].'';
				}
                else
				{
					$pathway_str .= ' &raquo; <a href="'.$newpath['link'].'">'.$newpath['title'].'</a>';
				}
                $title_str .= ' &raquo; '.$newpath['title'];
            }
        }
    }
    $pathway_str .= '';
}
// !!!!!!!!!!!!!!!! //
build_pathway();

function builddiv_start($type = 0, $title = "No title set") 
{
	global $Template;
	if ($type == 1) 
	{
		echo '<div style="width: 659px; height: 29px; background: url(\''.$Template['path'].'/images/content-parting.jpg\') no-repeat;"><div style="padding: 2px 0px 0px 23px;"><font style="font-family: \'Times New Roman\', Times, serif; color: #640909;"><h2>'.$title.'</h2></font></div></div>';
		echo '<div style="background: url(\''.$Template['path'].'/images/light.jpg\') repeat; border-width: 1px; border-color: #000000; border-bottom-style: solid; margin: 0px 0px 5px 0px">';
		echo '<div class="contentdiv">';
	}
	else 
	{
		if ($title != "No title set") 
		{
			echo '<div style="width: 659px; height: 29px; background: url(\''.$Template['path'].'/images/content-parting2.jpg\') no-repeat;"><div style="padding: 2px 0px 0px 23px;"><font style="font-family: \'Times New Roman\', Times, serif; color: #640909;"><h2>'.$title.'</h2></font></div></div>';
			echo '<div style="background: url(\''.$Template['path'].'/images/light.jpg\') repeat; border-width: 1px; border-color: #000000; border-bottom-style: solid; margin: 0px 0px 5px 0px">';
			echo '<div class="contentdiv">';
		}
		else 
		{
			echo '<div style="background: url(\''.$Template['path'].'/images/light.jpg\') repeat; border-width: 1px; border-color: #000000; border-top-style: solid; border-bottom-style: solid; margin: 4px 0px 5px 0px">';
			echo '<div class="contentdiv">';
		}
	}
}


function builddiv_end() 
{
	echo '</div></div>';
}
?>