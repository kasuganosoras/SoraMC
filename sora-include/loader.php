<?php
//error_reporting(E_ALL);
define("errtext", "");
class Loader {
	
	public function frame() {
		include(ROOT . "/sora-include/data/config.php");
		// echo $saveConfig["SiteName"];
		$this->cfg = $saveConfig;
		echo $this::loadPage("panel.html", ROOT . "/sora-content/" . $saveConfig["Template"] . "/");
	}
	
	public function getPanelConfig() {
		include(ROOT . "/sora-include/data/config.php");
		$num = $this::getDaemonNum();
		$select = "<select id='DaemonId'>";
		for($i = 2;$i < count($num) + 2;$i++) {
			$daemon = $this::getDaemon($num[$i]);
			$select .= "<option value='" . $num[$i] . "'>ID:" . $daemon["id"] . " " . $daemon["name"] . " (" . $daemon["host"] . ":" . $daemon["port"] . ")</option>";
		}
		$select .= "</select>";
		$htmlpage = "<br>
							<br>
							<table style='width: 100%;margin: auto;line-height: 28px;' class='serverconfig'>
								<tr>
									<th>站点名称</th>
									<td>
										<input id='SiteName' value='" . $saveConfig["SiteName"] . "'>
									</td>
								</tr>
								<tr>
									<th>站点介绍</th>
									<td>
										<input id='Description' value='" . $saveConfig["Description"] . "'>
									</td>
								</tr>
								<tr>
									<th>通讯密码</th>
									<td>
										<input id='AesEnKey' value='" . $saveConfig["AesEnKey"] . "'>
									</td>
								</tr>
								<tr>
									<th>旧版通讯密码</th>
									<td>
										<input id='ConPasswd' value='" . $saveConfig["ConPasswd"] . "'>
									</td>
								</tr>
								<tr>
									<th>启用模板</th>
									<td>
										<input id='Template' value='" . $saveConfig["Template"] . "'>
									</td>
								</tr>
								<tr>
									<th>Daemon Id</th>
									<td>
										<!--<input id='DaemonId' value='" . $saveConfig["DaemonId"] . "'>-->
										" . $select . "
									</td>
								</tr>
								<tr>
									<th>日志服务器地址</th>
									<td>
										<input id='LogServer' value='" . $saveConfig["LogServer"] . "'>
									</td>
								</tr>
								<tr>
									<th>日志服务器端口</th>
									<td>
										<input id='LogServerPort' value='" . $saveConfig["LogServerPort"] . "'>
									</td>
								</tr>
								<tr>
									<th>超级权限管理员</th>
									<td>
										<span>" . $saveConfig["AdminUser"] . " (需要在配置中手动修改)</span>
									</td>
								</tr>
							</table>
							<br>
							<span> ● 请务必输入正确的通讯密码，否则会无法连接与登陆。</span>
							<br>
							<span> ● 出现问题时请手动修改 /sora-include/data/config.php 中的 AesEnKey 项。</span>
							<br>
							<span> ● 修改 Daemon 地址和端口后需要退出登录再进入生效。</span>
							<br>
							<br>
							<button class='submitconfig' onclick='submitpanelconfig()'>     保存面板设置      </button>
							<script>
								function submitpanelconfig() {
									var sn = document.getElementById('SiteName').value;
									var dn = document.getElementById('Description').value;
									var ak = document.getElementById('AesEnKey').value;
									var cp = document.getElementById('ConPasswd').value;
									var te = document.getElementById('Template').value;
									var di = $('#DaemonId option:selected').val();
									var lh = document.getElementById('LogServer').value;
									var lp = document.getElementById('LogServerPort').value;
									htmlobj2=$.ajax({
										url:\"?do=savepanelconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
										},
										data: {
											SiteName: sn,
											Description: dn,
											AesEnKey: ak,
											ConPasswd: cp,
											Template: te,
											DaemonId: di,
											LogServer: lh,
											LogServerPort: lp
										}
									});
								}
							</script>
						";
		return $htmlpage;
	}
	
	public function panelConfigTemplate() {
		return "<?php
\$saveConfig = Array(
	'SiteName' => '{SiteName}',
	'Description' => '{Description}',
	'AdminUser' => '{AdminUser}',
	'AesEnKey' => '{AesEnKey}',
	'ConPasswd' => '{ConPasswd}',
	'Template' => '{Template}',
	'DaemonId' => {DaemonId},
	'LogServer' => '{LogServer}',
	'LogServerPort' => {LogServerPort}
);";
	}
	
	public function daemonConfigTemplate() {
		return '<?php
$daemon_id = {id};
$daemon_name = "{name}";
$daemon_host = "{host}";
$daemon_port = {port};
$daemon_desc = "{desc}";';
	}
	
	public function savePanelConfig($array) {
		if(($array["SiteName"] == "") || ($array["Description"] == "") || ($array["AesEnKey"] == "") || ($array["ConPasswd"] == "") || ($array["Template"] == "") || ($array["DaemonId"] == "") || ($array["LogServer"] == "") || ($array["LogServerPort"] == "")) {
			return false;
		}
		include_once(ROOT . "/sora-include/data/config.php");
		$SiteName = $array["SiteName"];
		$Description = $array["Description"];
		$AdminUser = $saveConfig["AdminUser"];
		$AesEnKey = $array["AesEnKey"];
		$ConPasswd = $array["ConPasswd"];
		$Template = $array["Template"];
		$DaemonId = $array["DaemonId"];
		$LogServer = $array["LogServer"];
		$LogServerPort = $array["LogServerPort"];
		$cf = $this::panelConfigTemplate();
		$cf = str_replace("{SiteName}", $SiteName, $cf);
		$cf = str_replace("{Description}", $Description, $cf);
		$cf = str_replace("{AdminUser}", $AdminUser, $cf);
		$cf = str_replace("{AesEnKey}", $AesEnKey, $cf);
		$cf = str_replace("{ConPasswd}", $ConPasswd, $cf);
		$cf = str_replace("{Template}", $Template, $cf);
		$cf = str_replace("{DaemonId}", $DaemonId, $cf);
		$cf = str_replace("{LogServer}", $LogServer, $cf);
		$cf = str_replace("{LogServerPort}", $LogServerPort, $cf);
		@file_put_contents(ROOT . "/sora-include/data/config.php", $cf);
		return true;
	}
	
	public function saveDaemonConfig($arr) {
		if(($arr["did"] == "") || ($arr["name"] == "") || ($arr["host"] == "") || ($arr["port"] == "")) {
			return false;
		}
		if(@file_exists(ROOT . "/sora-include/data/daemons/daemon_" . $arr["did"] . ".php")) {
			include(ROOT . "/sora-include/data/daemons/daemon_" . $arr["did"] . ".php");
			$daemon_name = $arr["name"];
			$daemon_host = $arr["host"];
			$daemon_port = $arr["port"];
			$daemon_desc = $arr["desc"];
			$cfg = $this::daemonConfigTemplate();
			$cfg = str_replace("{id}", $daemon_id, $cfg);
			$cfg = str_replace("{name}", $daemon_name, $cfg);
			$cfg = str_replace("{host}", $daemon_host, $cfg);
			$cfg = str_replace("{port}", $daemon_port, $cfg);
			$cfg = str_replace("{desc}", $daemon_desc, $cfg);
			@file_put_contents(ROOT . "/sora-include/data/daemons/daemon_" . $arr["did"] . ".php", $cfg);
			return true;
		} else {
			return false;
		}
	}
	
	public function addDaemonConfig($arr) {
		if(($arr["name"] == "") || ($arr["host"] == "") || ($arr["port"] == "")) {
			return false;
		}
		$daemon_id = rand(0, 999999);
		$daemon_name = $arr["name"];
		$daemon_host = $arr["host"];
		$daemon_port = $arr["port"];
		$daemon_desc = $arr["desc"];
		$cfg = $this::daemonConfigTemplate();
		$cfg = str_replace("{id}", $daemon_id, $cfg);
		$cfg = str_replace("{name}", $daemon_name, $cfg);
		$cfg = str_replace("{host}", $daemon_host, $cfg);
		$cfg = str_replace("{port}", $daemon_port, $cfg);
		$cfg = str_replace("{desc}", $daemon_desc, $cfg);
		@file_put_contents(ROOT . "/sora-include/data/daemons/daemon_" . $daemon_id . ".php", $cfg);
		return true;
	}
	
	public function getDaemon($daemonid) {
		if(@file_exists(ROOT . "/sora-include/data/daemons/daemon_" . $daemonid . ".php")) {
			include(ROOT . "/sora-include/data/daemons/daemon_" . $daemonid . ".php");
			$daemon = Array(
				'id' => $daemon_id,
				'name' => $daemon_name,
				'host' => $daemon_host,
				'port' => $daemon_port,
				'desc' => $daemon_desc
			);
			return $daemon;
		} else {
			return null;
		}
	}
	
	public function deleteDaemonConfig($arr) {
		if(@file_exists(ROOT . "/sora-include/data/daemons/daemon_" . $arr["did"] . ".php")) {
			@unlink(ROOT . "/sora-include/data/daemons/daemon_" . $arr["did"] . ".php");
			return true;
		} else {
			return false;
		}
	}
	
	public function getDaemonNum() {
		$path = ROOT . "/sora-include/data/daemons/./";
		$file = scandir($path);
		$list = Array();
		for($i = 0;$i < count($file); $i++) {
			if($file[$i] !== "." && $file[$i] !== "..") {
				$list[$i] = str_replace("daemon_", "", str_replace(".php", "", $file[$i]));
			}
		}
		return $list;
	}
	
	public function getDaemonList() {
		$num = $this::getDaemonNum();
		$daemonlist = "<table style='width: 100%;margin: auto;line-height: 28px;'>
								<tr>
									<th style='width: 8%'>ID</th>
									<th>名称</th>
									<th>地址</th>
									<th>端口</th>
									<th style='width: 30%'>说明</th>
									<th style='width: 25%'>操作</th>
								</tr>
								";
		for($s = 2;$s < count($num) + 2;$s++) {
			$daemon = $this::getDaemon($num[$s]);
			$daemonlist .= "<tr class='daemoncfg'>
									<td>" . $daemon["id"] . "</td>
									<td onclick='change(" . $daemon["id"] . ")' id='show_" . $daemon["id"] . "_name'>" . $daemon["name"] . "</td>
									<td onclick='change(" . $daemon["id"] . ")' id='show_" . $daemon["id"] . "_host'>" . $daemon["host"] . "</td>
									<td onclick='change(" . $daemon["id"] . ")' id='show_" . $daemon["id"] . "_port'>" . $daemon["port"] . "</td>
									<td onclick='change(" . $daemon["id"] . ")' id='show_" . $daemon["id"] . "_desc'>" . $daemon["desc"] . "</td>
									<td>
										<button class='submitconfig' onclick='change(" . $daemon["id"] . ")'>修改</button>
										<div class='s16px'></div>
										<button class='submitconfig' onclick='deletedaemon(" . $daemon["id"] . ")'>删除</button>
									</td>
								</tr>
								<tr id='changedaemon_" . $daemon["id"] . "' style='display: none;' class='daemonconfig'>
									<td></td>
									<td>
										<input id='daemon_" . $daemon["id"] . "_name' value='" . $daemon["name"] . "'>
									</td>
									<td>
										<input id='daemon_" . $daemon["id"] . "_host' value='" . $daemon["host"] . "'>
									</td>
									<td>
										<input id='daemon_" . $daemon["id"] . "_port' value='" . $daemon["port"] . "'>
									</td>
									<td>
										<input id='daemon_" . $daemon["id"] . "_desc' value='" . $daemon["desc"] . "' style='width: 90%;'>
									</td>
									<td style='width: 25%'>
										<button class='submitconfig' onclick='savechange(" . $daemon["id"] . ")'>确定</button>
										<div class='s16px'></div>
										<button class='submitconfig' onclick='cancel(" . $daemon["id"] . ")'>取消</button>
									</td>
								</tr>
							";
		}
		$daemonlist .= "</table>
							<script>
								function change(id) {
									document.getElementById('changedaemon_' + id).style.display = '';
								}
								function cancel(id) {
									document.getElementById('changedaemon_' + id).style.display = 'none';
								}
								function savechange(id) {
									var daemon_name = document.getElementById('daemon_' + id + '_name').value;
									var daemon_host = document.getElementById('daemon_' + id + '_host').value;
									var daemon_port = document.getElementById('daemon_' + id + '_port').value;
									var daemon_desc = document.getElementById('daemon_' + id + '_desc').value;
									htmlobj2=$.ajax({
										url:\"?do=savedaemonconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
										},
										success: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
											$('#show_' + id + '_name').html(daemon_name);
											$('#show_' + id + '_host').html(daemon_host);
											$('#show_' + id + '_port').html(daemon_port);
											$('#show_' + id + '_desc').html(daemon_desc);
											cancel(id);
										},
										data: {
											did: id,
											name: daemon_name,
											host: daemon_host,
											port: daemon_port,
											desc: daemon_desc
										}
									});
								}
								function deletedaemon(id) {
									htmlobj3=$.ajax({
										url:\"?do=deldaemonconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj3.responseText.replace(/提示：/g, \"\"));
										},
										success: function(){
											window.parent.frames.showmsg(htmlobj3.responseText.replace(/提示：/g, \"\"));
											location = '?page=daemon';
										},
										data: {
											did: id
										}
									});
								}
							</script>";
		return $daemonlist;
	}
	
	public function addDaemon() {
		return "<br>
							<br>
							<table style='width: 100%;margin: auto;line-height: 28px;'>
								<tr>
									<th>Daemon 名称</th>
									<th>Daemon 地址</th>
									<th>Daemon 端口</th>
									<th style='width: 30%'>Daemon 说明</th>
									<th style='width: 25%'></th>
								</tr>
								<tr class='daemonconfig' style='background: #FFF;'>
									<td>
										<input id='daemon_add_name'>
									</td>
									<td>
										<input id='daemon_add_host'>
									</td>
									<td>
										<input id='daemon_add_port'>
									</td>
									<td>
										<input id='daemon_add_desc' style='width: 90%;'>
									</td>
									<td style='width: 25%'>
										<button class='submitconfig' onclick='addDaemon()'>确定</button>
									</td>
								</tr>
							</table>
							<script>
								function addDaemon() {
									var daemon_name = document.getElementById('daemon_add_name').value;
									var daemon_host = document.getElementById('daemon_add_host').value;
									var daemon_port = document.getElementById('daemon_add_port').value;
									var daemon_desc = document.getElementById('daemon_add_desc').value;
									htmlobj2=$.ajax({
										url:\"?do=adddaemonconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
										},
										success: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
											location = '?page=daemon';
										},
										data: {
											name: daemon_name,
											host: daemon_host,
											port: daemon_port,
											desc: daemon_desc
										}
									});
								}
							</script>";
	}
	
	public function checkLogin() {
		if($this::getLoginStatus() === false) {
			if($_POST["username"] && $_POST["password"]) {
				//$trylogin = $this::userLogin($_POST);
				switch($this::userLogin($_POST)) {
					case 200:
						include(ROOT . "/sora-include/data/config.php");
						$keys = $saveConfig["AesEnKey"];
						$token = $this::serverLogin(Array('action' => 'login', 'key' => $keys, 'args' => ''));
						if($token == "Connect Failed") {
							$token = "";
						} elseif($token == "Auth Failed") {
							Header("Content-type: text/html", true, 500);
							echo "无法与 Daemon 建立连接，原因：" . $token;
							exit;
						}
						SESSION_START();
						$_SESSION["user"] = $_POST["username"];
						$_SESSION["token"] = $token;
						exit;
						break;
					case 403:
						Header("Content-type: text/html", true, 500);
						echo "用户名或密码错误";
						exit;
					case 404:
						Header("Content-type: text/html", true, 500);
						echo "用户名不存在或禁止登陆";
						exit;
					case 500:
						Header("Content-type: text/html", true, 500);
						echo "用户名不合法";
						exit;
					default:
						Header("Content-type: text/html", true, 500);
						echo "系统内部错误";
						exit;
				}
			} else {
				include(ROOT . "/sora-include/data/config.php");
				$this->cfg = $saveConfig;
				echo $this::loadPage("login.html", ROOT . "/sora-content/" . $saveConfig["Template"] . "/");
				echo "<script>window.onload = function (){if(self != top){top.location.href='./';}}</script>";
				exit;
			}
		} else {
			return;
		}
	}
	
	public function userLogin($userinfo) {
		if(preg_match("/^[A-Za-z0-9\-\_]+$/", $userinfo["username"])) {
			if(file_exists(ROOT . "/sora-include/data/users/" . $userinfo["username"] . ".php")) {
				include_once(ROOT . "/sora-include/data/users/" . $userinfo["username"] . ".php");
				if($userinfo["password"] == $password) {
					return 200;
				} else {
					return 403;
				}
			} else {
				return 404;
			}
		} else {
			return 500;
		}
	}
	
	public function getLoginStatus() {
		SESSION_START();
		if($_SESSION["user"] == "") {
			return false;
		} else {
			return true;
		}
	}
	
	public function checkPerm($perm, $puser) {
		$perm = str_replace(".html", "", $perm);
		if($perm == "view.page.login") {
			return true;
		}
		if(file_exists(ROOT . "/sora-include/data/users/" . $puser . ".php")) {
			include_once(ROOT . "/sora-include/data/users/" . $puser . ".php");
			if(stristr($permlist, "admin;")) {
				return true;
			} elseif(stristr($permlist, $perm)) {
				return true;
			} elseif($perm == "view.page.panel") {
				return true;
			} elseif($perm == "view.page.login") {
				return true;
			} elseif($perm == "system.control.logout") {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getPerm($puser) {
		if($puser == "") {
			return "Permission denied";
		}
		if(file_exists(ROOT . "/sora-include/data/users/" . $puser . ".php")) {
			include(ROOT . "/sora-include/data/users/" . $puser . ".php");
			return $permlist;
		} else {
			return "User Not Found";
		}
	}
	
	public function loadPage($pageName, $pagePath) {
		include(ROOT . "/sora-include/data/config.php");
		$conf = $saveConfig;
		SESSION_START();
		$puser = $_SESSION["user"];
		if(!file_exists($pagePath . $pageName)) {
			echo $this::errorPage(404);
			exit;
		}
		if($pageName !== "login.html") {
			if($this::checkPerm("view.page." . $pageName, $puser) == false) {
				$this::inFramePage(403, "view.page." . $pageName, $this::getPerm($puser));
				exit;
			}
		}
		$filecontent = file_get_contents($pagePath . $pageName);
		$filecontent = str_replace("{SITENAME}", $conf["SiteName"], $filecontent);
		$filecontent = str_replace("{CONTENTDIR}", "./sora-content", $filecontent);
		$filecontent = str_replace("{TEMPLATE}", $conf["Template"], $filecontent);
		$filecontent = str_replace("{SYSTEMINFO}", $this::systemInfo(), $filecontent);
		$filecontent = str_replace("{SERVERCONFIG}", $this::serverConfig(), $filecontent);
		$filecontent = str_replace("{SYSTEMCONFIG}", $this::systemConfig(), $filecontent);
		$filecontent = str_replace("{PANELCONFIG}", $this::getPanelConfig(), $filecontent);
		$filecontent = str_replace("{USERNAME}", $puser, $filecontent);
		$filecontent = str_replace("{DAEMONLIST}", $this::getDaemonList(), $filecontent);
		$filecontent = str_replace("{DAEMONADD}", $this::addDaemon(), $filecontent);
		$filecontent = str_replace("{CONSOLELOGURL}", "http://" . $conf["LogServer"] . ":" . $conf["LogServerPort"] . "/log/" . $_SESSION["token"], $filecontent);
		$filecontent = str_replace("{CHATLOGURL}", "http://" . $conf["LogServer"] . ":" . $conf["LogServerPort"] . "/chat/" . $_SESSION["token"], $filecontent);
		return $filecontent;
	}
	
	public function inFramePage($status, $perm, $haveperm) {
		switch($status) {
			case 403:
				$perm = str_replace(".html", "", $perm);
				if($haveperm == "") {
					$haveperm = "无权限";
				}
				echo $this::framePageTemplate("访问请求被服务器拒绝", "访问所需的权限", $perm, "您所拥有的权限", str_replace(";", "<br>", $haveperm));
				break;
		}
	}
	
	public function framePageTemplate($bigtitle, $title, $description, $title2, $description2) {
		return "<html>
	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		<title>" . $title . "</title>
		<style type='text/css'>
			body {
				background: #ffc107;
				color: #FFF;
			}
			.box {
				margin-top: 32px;
				margin-left: 64px;
			}
			.box face {
				font-size: 128px;
			}
			.box code {
				margin-top: -8px;
				display: inline-block;
				font-size: 15px;
				padding: 8px;
				background: rgba(0,0,0,0.2);
				border-radius: 8px;
				line-height: 20px;
				box-shadow: inset 0px 0px 16px rgba(0,0,0,0.3);
			}
			.box footer {
				margin-top: 64px;
				font-size: 16px;
				border-top: 3px solid #FFF;
				width: 512px;
				padding-top: 8px;
			}
			.box footer version {
				font-size: 8px;
				vertical-align: top;
				margin-left: 5px;
				margin-right: 8px;
			}
		</style>
	</head>
	<body>
		<div class='box'>
			<face>:(</face>
			<h1>" . $bigtitle . "</h1>
			<h3>" . $title . "</h3>
			<code>" . $description . "</code>
			<h3>" . $title2 . "</h3>
			<code>" . $description2 . "</code>
			<footer>SoraMC<version>10.3</version>Powerful Minecraft Panel</footer>
		</div>
	</body>
</html>";
	}
	
	public function clearBOM($text){
    if(substr($text, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $text = substr($text, 9);
		return $text;
	}
	
	public function plugin() {
		if($_GET["s"]) {
			passthru(ROOT . "/sora-include/plugins/sys_info.exe>C:/test.txt");
			exit;
		}
	}
	
	public function router() {
		if(preg_match("/^[A-Za-z0-9\-]+$/", $_GET["page"])) {
			include(ROOT . "/sora-include/data/config.php");
			echo $this::loadPage($_GET["page"] . ".html", ROOT . "/sora-content/" . $saveConfig["Template"] . "/");
			exit;
		} elseif($_GET["command"]) {
			SESSION_START();
			$puser = $_SESSION["user"];
			if($this::checkPerm("system.control.sendcommand", $puser) == false) {
				Header("Content-type: text/html", true, 500);
				echo "Permission denied";
				exit;
			}
			include(ROOT . "/sora-include/data/config.php");
			$keys = $saveConfig["AesEnKey"];
			$runcmd = $this::serverConnect(Array('action' => 'sendcommand', 'token' => $keys, 'args' => base64_encode($_GET["command"])));
			if($runcmd == "200 OK") {
				echo "Successful";
				exit;
			} else {
				Header("Content-type: text/html", true, 500);
				echo "错误：" . $runcmd;
				exit;
			}
		} elseif($_GET["message"]) {
			SESSION_START();
			$puser = $_SESSION["user"];
			if($this::checkPerm("system.control.sendmessage", $puser) == false) {
				Header("Content-type: text/html", true, 500);
				echo "Permission denied";
				exit;
			}
			include(ROOT . "/sora-include/data/config.php");
			$keys = $saveConfig["AesEnKey"];
			$runcmd = $this::serverConnect(Array('action' => 'sendmessage', 'token' => $keys, 'args' => base64_encode($_GET["message"])));
			if($runcmd == "200 OK") {
				echo "Successful";
				exit;
			} else {
				Header("Content-type: text/html", true, 500);
				echo "错误：" . $runcmd;
				exit;
			}
		} elseif($_GET["info"]) {
			SESSION_START();
			$puser = $_SESSION["user"];
			if($this::checkPerm("system.info.online", $puser) == false) {
				Header("Content-type: text/html", true, 500);
				echo "Permission denied";
				exit;
			}
			include(ROOT . "/sora-include/data/config.php");
			$keys = $saveConfig["AesEnKey"];
			$cfgFile = $this::serverConnect(Array('action' => 'getserverconfig', 'token' => $keys, 'args' => ''));
			$mcServerPort = $this::getConfigTag($cfgFile, "server-port");
			$daemon = $this::getDaemon($saveConfig["DaemonId"]);
			$arrays = $this::serverQuery($daemon["host"], $mcServerPort);
			if($arrays["version"]) {
				echo $arrays["online"] . "/" . $arrays["max"];
				exit;
			} else {
				echo "--/--";
				exit;
			}
		} elseif($_GET["do"]) {
			SESSION_START();
			$puser = $_SESSION["user"];
			if($this::checkPerm("system.control." . $_GET["do"], $puser) == false) {
				Header("Content-type: text/html", true, 500);
				echo "Permission denied";
				exit;
			}
			switch($_GET["do"]) {
				case "start":
					$docmd = "start";
					break;
				case "stop":
					$docmd = "stop";
					break;
				case "restart":
					$docmd = "restart";
					break;
				case "status":
					$docmd = "status";
					break;
				case "version":
					$docmd = "daemonversion";
					break;
				case "encrypt":
					$docmd = "daemonencrypt";
					break;
				case "saveconfig":
					if($_POST["configtext"]) {
						$docmd = "saveconfig";
						$args = base64_encode($_POST["configtext"]);
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to upload";
					}
					break;
				case "savepanelconfig":
					if($this::savePanelConfig($_POST)) {
						Header("Content-type: text/html", true, 500);
						echo "提示：Successful save config";
						exit;
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to upload";
						exit;
					}
					break;
				case "savedaemonconfig":
					if($this::saveDaemonConfig($_POST)) {
						Header("Content-type: text/html", true, 200);
						echo "提示：Successful save config";
						exit;
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to upload";
						exit;
					}
					break;
				case "deldaemonconfig":
					if($this::deleteDaemonConfig($_POST)) {
						Header("Content-type: text/html", true, 200);
						echo "提示：Successful delete config";
						exit;
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to delete";
						exit;
					}
					break;
				case "adddaemonconfig":
					if($this::addDaemonConfig($_POST)) {
						Header("Content-type: text/html", true, 200);
						echo "提示：Successful save config";
						exit;
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to upload";
						exit;
					}
					break;
				case "savesystemconfig":
					if($_POST["corename"] && $_POST["jvmmaxmr"] && $_POST["javapath"]) {
						if(!preg_match("/^[A-Za-z0-9\-\_\[\]\#\+\.]+$/", $_POST["corename"])) {
							Header("Content-type: text/html", true, 500);
							preg_match("/^[A-Za-z0-9\-\_\[\]\#\+\.]+$/", $_POST["corename"], $arr);
							$errpart = str_replace($arr[0], "", $_POST["corename"]);
							echo "错误：为防止乱码，请输入仅包含 A-Za-z0-9 - _ [ ] . 的文件名<br>你提交的信息中包含不合法的内容：" . htmlspecialchars($errpart);
							exit;
						}
						if(!preg_match("/^[A-Za-z0-9\-\_\[\]\+\.\/\\\ \:]+$/", $_POST["javapath"])) {
							Header("Content-type: text/html", true, 500);
							preg_match("/^[A-Za-z0-9\-\_\[\]\#\+\.]+$/", $_POST["javapath"], $arr);
							$errpart = str_replace($arr[0], "", $_POST["javapath"]);
							echo "错误：为防止乱码，请输入仅包含 A-Za-z0-9 - + _ [ ] . / \ 的路径<br>你提交的信息中包含不合法的内容：" . htmlspecialchars($errpart);
							exit;
						}
						if(!preg_match("/^[0-9]+$/", $_POST["jvmmaxmr"])) {
							Header("Content-type: text/html", true, 500);
							echo "错误：最大内存必须为数字且为整数";
							exit;
						}
						$docmd = "savesystemconfig";
						$args = base64_encode(json_encode(Array(
							'corename' => $_POST["corename"],
							'jvmmaxmr' => $_POST["jvmmaxmr"],
							'javapath' => $_POST["javapath"]
						)));
					} else {
						Header("Content-type: text/html", true, 500);
						echo "错误：Not config to upload";
						exit;
					}
					break;
				case "logout":
					SESSION_START();
					$_SESSION["user"] = "";
					echo "<script>location='./';</script>";
					exit;
				default:
					Header("Content-type: text/html", true, 500);
					echo "错误：Command Not Found";
					exit;
			}
			include(ROOT . "/sora-include/data/config.php");
			$keys = $saveConfig["AesEnKey"];
			$runcmd = $this::serverConnect(Array('action' => $docmd, 'token' => $keys, 'args' => $args));
			if($runcmd == "200 OK") {
				echo "Successful";
				exit;
			} else {
				Header("Content-type: text/html", true, 500);
				echo "提示：" . $runcmd;
				exit;
			}
		} elseif(empty($_GET["page"])) {
			return;
		} else {
			echo $this::errorPage(403);
			exit;
		}
	}
	
	public function errorPage($status) {
		$errorPage = file_get_contents(ROOT . "/sora-content/error/" . $status . ".html");
		$errorPage = str_replace("{PATH}", DOCROOT . "/sora-content", $errorPage);
		$errorPage = str_replace("{HOME}", DOCROOT, $errorPage);
		return $errorPage;
	}
	
	public function serverConnect($args) {
		include(ROOT . "/sora-include/data/config.php");
		include_once(ROOT . "/sora-include/plugins/AES.php");
		$keys = $saveConfig["AesEnKey"];
		$daemon = $this::getDaemon($saveConfig["DaemonId"]);
		$port = $daemon["port"];
		$host = $daemon["host"];
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if(@socket_connect($socket , $host, $port)) {
			$aes = new AES($bit = 256, $key = md5($keys), $iv = md5($keys), $mode = 'cfb');
			$str = $aes->encrypt(json_encode($args));
			if($str){
				socket_write($socket, $str, strlen($str));
			}
			while($t = socket_read($socket, 8192)){
				$ret = json_decode($aes->decrypt($t), true);
				if($ret["status"] == 200) {
					return "200 OK";
					break;
				} else {
					$fallback = json_decode($aes->decrypt($t), true);
					return $fallback["description"];
					break;
				}
			}
			socket_close($socket);
		} else {
			return "Connect Failed";
		}
	}
	
	public function serverLogin($args) {
		include(ROOT . "/sora-include/data/config.php");
		include_once(ROOT . "/sora-include/plugins/AES.php");
		$keys = $saveConfig["AesEnKey"];
		$daemon = $this::getDaemon($saveConfig["DaemonId"]);
		$port = $daemon["port"];
		$host = $daemon["host"];
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if(@socket_connect($socket , $host, $port)) {
			$aes = new AES($bit = 256, $key = md5($keys), $iv = md5($keys), $mode = 'cfb');
			$str = $aes->encrypt(json_encode($args));
			if($str){
				socket_write($socket, $str, strlen($str));
			}
			while($t = socket_read($socket, 8192)){
				$ret = json_decode($aes->decrypt($t), true);
				if($ret["status"] == 200) {
					return $ret["description"];
					break;
				} else {
					return $ret["description"];
					break;
				}
			}
			socket_close($socket);
		} else {
			return "Connect Failed";
		}
	}
	
	public function systemInfo() {
		include(ROOT . "/sora-include/data/config.php");
		$conf = $saveConfig;
		return "
<table style='width: 100%;margin: auto;line-height: 28px;'>
	<tr>
		<th>操作系统</th>
		<td>" . php_uname() . "</td>
	</tr>
	<tr>
		<th>PHP运行环境</th>
		<td>" . php_sapi_name() . "</td>
	</tr>
	<tr>
		<th>进程用户名</th>
		<td>" . Get_Current_User() . "</td>
	</tr>
	<tr>
		<th>PHP版本号</th>
		<td>" . PHP_VERSION . "</td>
	</tr>
	<tr>
		<th>HTTP服务器引擎</th>
		<td>" . $_SERVER['SERVER_SOFTWARE'] . "</td>
	</tr>
	<tr>
		<th>服务器CPU核心</th>
		<td>" . $_SERVER['PROCESSOR_IDENTIFIER'] . "</td>
	</tr>
	<tr>
		<th>服务器语言</th>
		<td>" . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "</td>
	</tr>
	<tr>
		<th>SoraMC 版本</th>
		<td>" . $this::getSoraMC("version") . "</td>
	</tr>
	<tr>
		<th>Daemon 版本</th>
		<td><span id='daemonversion'></span></td>
	</tr>
	<tr>
		<th>Daemon 加密协议</th>
		<td><span id='daemonencrypt'></span></td>
	</tr>
	<tr>
		<th>支持库名称</th>
		<th>是否启用</th>
	</tr>
	<tr>
		<th>Socket 支持</th>
		<td>" . $this::getSoraMC("socket") . "</td>
	</tr>
	<tr>
		<th>MySQL 支持</th>
		<td>" . $this::getSoraMC("mysql") . "</td>
	</tr>
	<tr>
		<th>mbString 支持</th>
		<td>" . $this::getSoraMC("mbstring") . "</td>
	</tr>
	<tr>
		<th>GD图形库支持</th>
		<td>" . $this::getSoraMC("gd") . "</td>
	</tr>
	<tr>
		<th>iconv 支持</th>
		<td>" . $this::getSoraMC("iconv") . "</td>
	</tr>
	<tr>
		<td>
			<img src='sora-content/" . $conf["Template"] . "/assets/alipay.jpg' style='width: 128px;height: 128px;'>
		</td>
		<td>
			<span>如果你觉得这个软件好的话，欢迎给予我一些赞助~</span>
			<br>
			<span>所有赞助将会用于以后的开发和维护。</span>
			<br>
			<span>感谢大家的支持，我会越做越好的 :)</span>
			<br>
			<span>官方网站：<a href='https://www.soramc.com/'>www.soramc.com</a><div class='s16px'></div>交流群：602945616</span>
		</td>
	</tr>
</table>";
	}
	
	public function getSoraMC($info) {
		switch($info) {
			case "version":
				return "10.0 (2018.01.11) [Ver.10.0.31011] Release";
				break;
			case "socket":
				if(function_exists("socket_create")) {
					return "<span style='color: #00B100'>已启用</span>";
				} else {
					return "<span style='color: #FF0000'>已禁用</span>";
				}
				break;
			case "mysql":
				if(function_exists("mysql_connect")) {
					return "<span style='color: #00B100'>已启用</span>";
				} else {
					return "<span style='color: #FF0000'>已禁用</span>";
				}
				break;
			case "mbstring":
				if(function_exists("mb_substr")) {
					return "<span style='color: #00B100'>已启用</span>";
				} else {
					return "<span style='color: #FF0000'>已禁用</span>";
				}
				break;
			case "gd":
				if(function_exists("gd_info")) {
					return "<span style='color: #00B100'>已启用</span>";
				} else {
					return "<span style='color: #FF0000'>已禁用</span>";
				}
				break;
			case "iconv":
				if(function_exists("iconv")) {
					return "<span style='color: #00B100'>已启用</span>";
				} else {
					return "<span style='color: #FF0000'>已禁用</span>";
				}
				break;
		}
	}
	
	public function serverConfig() {
		include(ROOT . "/sora-include/data/config.php");
		$keys = $saveConfig["AesEnKey"];
		$cfgFile = $this::serverConnect(Array('action' => 'getserverconfig', 'token' => $keys, 'args' => ''));
		return $this::getConfigHtml($cfgFile) . $this::getConfigJs($cfgFile) . "<button class='submitconfig' onclick='submitconfig()'>     保存核心设置      </button>";
	}
	
	public function systemConfig() {
		include(ROOT . "/sora-include/data/config.php");
		$keys = $saveConfig["AesEnKey"];
		$cfgFile = json_decode($this::serverConnect(Array('action' => 'getsystemconfig', 'token' => $keys, 'args' => '')), true);
		$htmlpage = "<br>
							<br>
							<table style='width: 100%;margin: auto;line-height: 28px;' class='serverconfig'>
								<tr>
									<th>服务端核心名称</th>
									<td>
										<input id='corename' value='" . $cfgFile["corename"] . "'>
									</td>
								</tr>
								<tr>
									<th>服务端最大内存</th>
									<td>
										<input id='jvmmaxmr' value='" . $cfgFile["jvmmaxmr"] . "'>
									</td>
								</tr>
								<tr>
									<th>Java 安装路径</th>
									<td>
										<input id='javapath' value='" . $cfgFile["javapath"] . "'>
									</td>
								</tr>
							</table>
							<br>
							<button class='submitconfig' onclick='submitsystemconfig()'>     保存基础设置      </button>
						";
		return $htmlpage;
	}
	
	public function getConfigTag($config, $need) {
		if(stristr($config, "\r\n")) {
			$gettag = explode("\r\n", $config);
		} else {
			$gettag = explode("\n", $config);
		}
		for($i = 0;$i < count($gettag);$i++) {
			$getkey = explode("=", $gettag[$i]);
			if($getkey[0] == $need) {
				return $getkey[1];
			}
		}
	}
	
	public function getConfigHtml($config) {
		$htmltext = "<span>此处是服务器核心配置，左侧为配置项，右侧为值。</span>
							<table style='width: 100%;margin: auto;line-height: 28px;' class='serverconfig'>";
		if(stristr($config, "\r\n")) {
			$gettag = explode("\r\n", $config);
		} else {
			$gettag = explode("\n", $config);
		}
		for($i = 0;$i < count($gettag);$i++) {
			$getkey = explode("=", $gettag[$i]);
			if(($getkey[0] !== "") && (mb_substr($getkey[0], 0, 1) !== "#")) {
				$keysname = str_replace("-", "_", str_replace(".", "_", $getkey[0]));
				$htmltext .= "
								<tr>
									<th>" . $getkey[0] . "</th>
									<td>
										<input id='cfg_" . $keysname . "' value='" . $getkey[1] . "'>
									</td>
								</tr>";
			}
		}
		return $htmltext . "
							</table>";
	}
	
	public function getConfigJs($config) {
		$htmltext = "
							<script type='text/javascript'>
								function submitconfig() {\n";
		$varstext = "									var config = \"";
		if(stristr($config, "\r\n")) {
			$gettag = explode("\r\n", $config);
			$enterkey = "\\r\\n";
		} else {
			$gettag = explode("\n", $config);
			$enterkey = "\\n";
		}
		for($i = 0;$i < count($gettag);$i++) {
			$getkey = explode("=", $gettag[$i]);
			if(($getkey[0] !== "") && (mb_substr($getkey[0], 0, 1) !== "#")) {
				$keysname = str_replace("-", "_", str_replace(".", "_", $getkey[0]));
				$htmltext .= "									var cfg_" . $keysname . " = document.getElementById('cfg_" . $keysname . "').value;\n";
				$varstext .= $getkey[0] . "=\" + cfg_" . $keysname . " + \"" . $enterkey;
			}
		}
		return $htmltext . $varstext . "\";
									console.log(config);
									htmlobj2=$.ajax({
										url:\"?do=saveconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
										},
										data: {
											configtext: \"#Minecraft server properties" . $enterkey . "#Fri Jan 12 01:16:47 CST 2018" . $enterkey . "\" + config
										}
									});
								}
								function submitsystemconfig() {
									var cn = document.getElementById('corename').value;
									var jr = document.getElementById('jvmmaxmr').value;
									var jp = document.getElementById('javapath').value;
									htmlobj2=$.ajax({
										url:\"?do=savesystemconfig\",
										type: 'POST',
										async:true,
										error: function(){
											window.parent.frames.showmsg(htmlobj2.responseText.replace(/提示：/g, \"\"));
										},
										data: {
											corename: cn,
											jvmmaxmr: jr,
											javapath: jp
										}
									});
								}
							</script>";
	}
	
	public function serverQuery($addres, $port = 25565, $timeout = 2) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => (int)$timeout, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => (int)$timeout, 'usec' => 0));
        if($socket === false || @socket_connect($socket, $addres, (int)$port) === false) {
            return false;
        }
        socket_send($socket, "\xFE\x01", 2, 0);
        $length = socket_recv($socket, $data, 512, 0);
        socket_close($socket);
        if($length < 4 || $data[0] !== "\xFF") {
            return false;
        }
        $data = substr($data, 3);
        $data = iconv('UTF-16BE', 'UTF-8', $data);
        if($data[1] === "\xA7" && $data[2] === "\x31") {
            $data = explode("\x00", $data);
            return Array(
                'motd' => $data[3],
                'online' => intval($Data[4]),
                'max' => intval($data[5]),
                'protocol' => intval($data[1]),
                'version' => $data[2],
            );
        }
        $data = explode("\xA7", $data);
        return Array(
            'motd' => substr($data[0], 0, -1),
            'online'  => isset($data[1]) ? intval($data[1]) : 0,
            'max' => isset($data[2]) ? intval($data[2]) : 0,
            'protocol' => 0,
            'version' => '1.3',
        );
    }
}