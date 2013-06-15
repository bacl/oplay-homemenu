# OPlay Home torrent statusAdd torrent status information to the home menu of media center Asus O!Play HDP R1 with firmware [amp_r10_icon](http://www.moservices.org/forum/viewtopic.php?f=12&t=1030)
**Screenshot:** ![alt text](https://github.com/bacl/oplay-mymod/raw/master/printscreen_homeMenu.jpg "Screenshot")## Pre-requisites:+ Realtek 1073 based player  (ex: Asus O!Play HDP R1)+ [moServices](https://sites.google.com/site/farvoice/) instaled + [Transmission](http://www.transmissionbt.com/) with RPC enabled
+ HTTP server with PHP## Testing environment:+ Asus O!Play HDP R1 with firmware [amp_r10_icon](http://www.moservices.org/forum/viewtopic.php?f=12&t=1030)+ [RaspberryPi](http://www.raspberrypi.org/) with [Raspbian](http://www.raspbian.org/) running Transmission and nginx+PHP5 
	## Install 

*Two-step instalation:*1. Add *php_script* to the HTTP server 	Edit transmission_info.php and update the folowing variables to your Transmission configuration:	```php	$HOST="192.168.1.92";	$PORT="8081";	$USER="transmission_username";	$PASSWORD="password";	```		The output of this script is plain text where each line as the downloaded percentage and torrent name;		**Example:**	```	52,3% - torrent1	12,0% - torrent2	100%  - torrent3	```2. Add to HomeMenu an text item to show the status info
	On the oplay, edit file `/usr/local/etc/mos/iconmenu/HomeMenu.rss`
 	2.1 around line 170 , inside `<onRefresh>` tag, after `/* get weather */` IF, paste the following code:
		*NOTE: change `torServerURL` to the url hostting the php script*	```javascript  	torServerURL="http://192.168.1.92/transmission_info.php";	torTempData="/tmp/transmission_info.str";		if (torTimer ==0) {		str_tor= getURL(torServerURL) ; 		writeStringToFile(torTempData, str_tor);  		torTimer=10;	}else{		torTimer-=1;		str_tor = readStringFromFile(torTempData); 		if(str_tor==null){			str_tor= getURL(torServerURL) ; 			writeStringToFile(torTempData, str_tor);  			torTimer=10;		}	}	```  	2.2 around line 470 , inside `<onEnter>` tag, after 
	 		weatherTimer = 0; 		weatherGet = 1; 	add 		torTimer=0; 		str_tor="";		2.3 around line 800 , after IP text tag add: 		<!-- torrents --> 		<text  offsetXPC=25 lines=6  offsetYPC=70 widthPC=70 heightPC=18 fontSize=10 foregroundColor=226:226:226 backgroundColor=-1:-1:-1 align=left tailDots=yes useBackgroundSurface="yes" redraw="yes">		   <script> 			str_tor; 		  </script> 		</text> 
3. Save and on the media center, navigate to HomeMenu or just reboot
 
   
 
 
## Other modifications to HomeMenu
### Show just time, with a bigger font and in a whiteish color
*Background: In current moServices update for gui hds42l(2013-06-10), it is shown date and time on a yellow color.*

	
+ Edit file `/usr/local/etc/mos/iconmenu/HomeMenu.rss`

+ Around line 740 set `fontSize=54`, `heightPC=9` and `foregroundColor=226:226:226`
			**Example:**
	```javascript  	<!-- time --> 	<text fontFile="/usr/local/etc/mos/iconmenu/time.ttf" offsetXPC=7.42 offsetYPC=9 widthPC=45 heightPC=9 fontSize=54 foregroundColor=226:226:226 backgroundColor=-1:-1:-1 align=left tailDots=yes useBackgroundSurface="yes" redraw="yes">	  <script> 		st_time; 	  </script> 	</text>	```   + Around line 210 comment or delete this lines:  		t = getStringArrayAt(s, 2); if( t &lt; 10 ) t = "0" + t; d = t + "."; 		t = getStringArrayAt(s, 1); if( t &lt; 10 ) t = "0" + t; d = d + t + " "; 	and add 		d="";			**Example:**		d="";	 
				/*		t = getStringArrayAt(s, 2); if( t &lt; 10 ) t = "0" + t; d = t + ".";		t = getStringArrayAt(s, 1); if( t &lt; 10 ) t = "0" + t; d = d + t + " ";		*/		
				

### Icons
 
Icons credits to: [Bogdan Mihaiciuc](http://bogo-d.deviantart.com/)

1. Downaload git `icons` folder
2. Copy them to a flash drive and connect it to media device
3. telnet to media device as *root*
4. copy icons files and overwrite the destination file at `/usr/local/etc/mos/iconmenu/images/`

	**Example**
	```sh
	cp /tmp/usbmounts/sda1/icons/* /usr/local/etc/mos/iconmenu/images/
	```
				 ### Wallpaper1. Download at: http://www.androidguys.com/wallpaper/minimal-27/ 2. Copy wallpaper to the internal storege of media device.	**Example:** telnet/ssh to media device as *root* and:	```sh	mkdir /usr/local/etc/mos/wallpaper  	cd /usr/local/etc/mos/wallpaper  	wget http://www.androidguys.com/wp-content/uploads/2013/04/minimal9.jpg 	```3. Edit file `/usr/local/etc/mos/iconmenu/iconmenu.conf` and change `<bginfo>` path like this:`<bginfo>/usr/local/etc/mos/wallpaper/minimal9.jpg</bginfo>` 