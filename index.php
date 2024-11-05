<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Instalar ControlDistrinef</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="instalar/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body>
	<header class="section-bg py-2 text-center">
		<div class="container">
			<h3 class="title">Instalar ControlDistrinef</h3>
		</div>
	</header>
	<div class="installation-section padding-bottom padding-top">
		<div class="container">
			<?php 
			//error_reporting(0);
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			function isExtensionAvailable($name){
				if (!extension_loaded($name)) {
					$response = false;
				} else {
					$response = true;
				}
				return $response;
			}
			function checkFolderPerm($name){
				$perm = substr(sprintf('%o', fileperms($name)), -4);
				if ($perm >= '0775') {
					$response = true;
				} else {
					$response = false;
				}
				return $response;
			}
			function tableRow($name, $details, $status){
				if ($status=='1') {
					$pr = '<i class="fas fa-check"></i>';
				}else{
					$pr = '<i class="fas fa-times"></i>';
				}
				echo "<tr><td>$name</td><td>$details</td><td>$pr</td></tr>";
			}
			function getWebURL(){   
				$base_url = (isset($_SERVER['HTTPS']) &&
					$_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
				$tmpURL = dirname(__FILE__);
				$tmpURL = str_replace(chr(92),'/',$tmpURL);
				$tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
				$tmpURL = ltrim($tmpURL,'/');
				$tmpURL = rtrim($tmpURL, '/');
				$tmpURL = str_replace('install','',$tmpURL);
				$base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL;
				if (substr("$base_url", -1=="/")) {
					$base_url = substr("$base_url", 0, -1);
				}
				return $base_url; 
			}

			function getStatus($arr){
				return true;
			}

			function replaceData($val,$arr){
				foreach ($arr as $key => $value) {
					$val = str_replace('{{'.$key.'}}', $value, $val);
				}
				return $val;
			}
			function setDataValue($val,$loc){
				$file = fopen($loc, 'w');
				fwrite($file, $val);
				fclose($file);
			}
			function sysInstall($sr,$pt){
				return true;
			}
			function importDatabase($pt){
					return true;

			}
			function setAdminEmail($pt){
					return true;
			}
			//------------->> Extension & Permission
			$requiredServerExtensions = [
				'BCMath', 'Ctype', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PDO','pdo_mysql', 'Tokenizer', 'XML'
			];

			$folderPermissions = [
				'bootstrap/cache/', 'storage/', 'storage/app/', 'storage/framework/', 'storage/logs/'
			];
			//------------->> Extension & Permission

			if (isset($_GET['action'])) {
				$action = $_GET['action'];
			}else {
				$action = "";
			}

			if ($action=='complete') {
				?>
				<div class="installation-wrapper pt-md-5">
					<ul class="installation-menu">
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-server"></i>
							</div>
							<h5 class="content">Requisitos<br>del servidor</h5>
						</li>
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-file-signature"></i>
							</div>
							<h5 class="content">Permisos<br>de archivo</h5>
						</li>
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-database"></i>
							</div>
							<h5 class="content">Información<br>de instalación</h5>
						</li>
						<li class="steps running">
							<div class="thumb">
								<i class="fas fa-check-circle"></i>
							</div>
							<h5 class="content">Instalación<br>completada</h5>
						</li>
					</ul>
				</div>
				<div class="installation-wrapper">
					<div class="install-content-area">
						<div class="install-item">
							<h3 class="title text-center">Instalación completada</h3>
							<div class="box-item">
								<div class="success-area text-center">
									<?php
									if ($_POST) {
										$alldata = $_POST;
										$db_name = $_POST['db_name'];
										$db_host = $_POST['db_host'];
										$db_user = $_POST['db_user'];
										$db_pass = $_POST['db_pass'];
										$username = $_POST['username'];
										$password = $_POST['password'];
			                            $siteurl = getWebURL();
										$app_key = base64_encode(random_bytes(32));
										$envcontent = "
APP_NAME=ControlDistrinef
APP_ENV=local
APP_KEY=base64:$app_key
APP_DEBUG=true
APP_URL=$siteurl

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=$db_host
DB_PORT=3306
DB_DATABASE=$db_name
DB_USERNAME=$db_user
DB_PASSWORD=$db_pass

PRESTA_HOST=185.47.244.83
PRESTA_DATABASE=presta17  
PRESTA_USERNAME=user_presta
PRESTA_PASSWORD=&a0aY3m0

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST='gasfriocalor.loading.net'
MAIL_PORT=587
MAIL_USERNAME='webmaster@gasfriocalor.com'
MAIL_PASSWORD='Hv6z6l@39'
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS='webmaster@gasfriocalor.com'
MAIL_FROM_NAME='{$APP_NAME}' 	

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME='{$APP_NAME}'
VITE_PUSHER_APP_KEY='{$PUSHER_APP_KEY}'
VITE_PUSHER_HOST='{$PUSHER_HOST}'
VITE_PUSHER_PORT='{$PUSHER_PORT}'
VITE_PUSHER_SCHEME='{$PUSHER_SCHEME}'
VITE_PUSHER_APP_CLUSTER='{$PUSHER_APP_CLUSTER}'

GFC_SCRAP_ID=1
DLED_SCRAP_ID=3

";
										$indexcontent = '<?php
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: " . $actual_link . "public/index.php");';
										$status = 'ok';
										$envpath = dirname(__DIR__, 1) . '\controlDistrinefAutoloadV02\.env';
										$indexpath = dirname(__DIR__, 1) . '\controlDistrinefAutoloadV02\index.php';
										file_put_contents($envpath, $envcontent);										
										if ($status == 'ok') {
											if(importDatabase($alldata)){		

												$servidor = $db_host; // Dirección del servidor MySQL
												$usuario = $db_user;    // Usuario de MySQL
												$contraseña = $db_pass; // Contraseña del usuario

												// Crear conexión
												$conn = new mysqli($servidor, $usuario, $contraseña);

												// Verificar conexión
												/* if ($conn->connect_error) {
													die("Error en la conexión: " . $conn->connect_error);
												} */

												// Crear la base de datos
												/* $sql = "CREATE DATABASE ".$db_name;
												if ($conn->query($sql) === TRUE) {
													echo "Base de datos creada exitosamente";
												} else {
													echo "Error al crear la base de datos: " . $conn->error;
												} */

												// Cerrar conexión
												$conn->close();

												$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
												$query = file_get_contents("instalar/database.sql");
												$stmt = $conn->prepare($query);
												$stmt->execute();

	         								}
											
											if(setAdminEmail($alldata)){
												$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
												$passwordHash = password_hash($password, PASSWORD_BCRYPT);
												//$2y$12$a9YSnsfCJNisq9YTQW3BOe.2g1.QHoWr02t4NOi1JjlkwJtBwVbaa
												$sql = "UPDATE users SET username='".$username."', password='".$passwordHash."' WHERE id=1";
												$stmt = $db->prepare($sql);
												$stmt->execute();
												echo '<div class="warning">
													<p class="text-danger lead my-3">Elimine la carpeta "instalar" del servidor.</p>
													</div>';
												echo '
													<div class="warning">
													<a href="'.getWebURL().'" class="theme-button choto">Ir al panel de administración</a>
													</div>';	
													
												file_put_contents($indexpath, $indexcontent);
											}
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}elseif($action=='info') {
				?>
				<div class="installation-wrapper pt-md-5">
					<ul class="installation-menu">
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-server"></i>
							</div>
							<h5 class="content">Requisitos<br>del servidor</h5>
						</li>
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-file-signature"></i>
							</div>
							<h5 class="content">Permisos<br>de archivo</h5>
						</li>
						<li class="steps running">
							<div class="thumb">
								<i class="fas fa-database"></i>
							</div>
							<h5 class="content">Información<br>de instalación</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-check-circle"></i>
							</div>
							<h5 class="content">Instalación<br>completada</h5>
						</li>
					</ul>
				</div>
				<div class="installation-wrapper">
					<div class="install-content-area">
						<div class="install-item">
							<h3 class="title text-center">Información de instalación</h3>
							<div class="box-item">
								<form action="?action=complete" method="post" class="information-form-area mb--20">
									<div class="info-item">
										<h5 class="font-weight-normal mb-2">URL del sitio web</h5>
										<div class="row">
											<div class="information-form-group col-12">
												<input name="url" value="<?php echo getWebURL(); ?>" type="text" required>
											</div>
										</div>
									</div>
									<div class="info-item">
										<h5 class="font-weight-normal mb-2">Detalles de la base de datos</h5>
										<div class="row">
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_name" placeholder="Nombre de la base de datos" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_host" placeholder="Host de base de datos" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_user" placeholder="Usuario de base de datos" required>
											</div>
											<div class="information-form-group col-sm-6">
												<input type="text" name="db_pass" placeholder="Contraseña de la base de datos">
											</div>
										</div>
									</div>
									<div class="info-item">
										<h5 class="font-weight-normal mb-3">Credencial de administrador</h5>
										<div class="row">
											<div class="information-form-group col-lg-3 col-sm-6">
												<label>Usuario</label>
												<input type="text" value="admin" class="bg-dark" name="username">
											</div>
											<div class="information-form-group col-lg-3 col-sm-6">
												<label>Clave</label>
												<input type="text" value="admin" class="bg-dark" name="password">
											</div>
										</div>
									</div>
									<div class="info-item">
										<div class="information-form-group text-right">
											<button type="submit" class="theme-button choto">Instalar ahora</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php
			}elseif ($action=='file') {
				?>
				<div class="installation-wrapper pt-md-5">
					<ul class="installation-menu">
						<li class="steps done">
							<div class="thumb">
								<i class="fas fa-server"></i>
							</div>
							<h5 class="content">Requisitos<br>del servidor</h5>
						</li>
						<li class="steps running">
							<div class="thumb">
								<i class="fas fa-file-signature"></i>
							</div>
							<h5 class="content">Permisos<br>de archivo</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-database"></i>
							</div>
							<h5 class="content">Información<br>de instalación</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-check-circle"></i>
							</div>
							<h5 class="content">Instalación<br>completada</h5>
						</li>
					</ul>
				</div>
				<div class="installation-wrapper">
					<div class="install-content-area">
						<div class="install-item">
							<h3 class="title text-center">Permisos de Archivo</h3>
							<div class="box-item">
								<div class="item table-area">
									<table class="requirment-table">
										<?php
										$error = 0;
										foreach ($folderPermissions as $key) {
											$folder_perm = checkFolderPerm($key);
											if ($folder_perm==true) {
												tableRow(str_replace("../", "", $key)," Permiso requerido: 0775 ",1);
											}else{
												$error += 1;
												tableRow(str_replace("../", "", $key)," Permiso requerido: 0775 ",0);
											}
										}
										$database = file_exists('instalar/database.sql');
										if ($database==true) {
											$error = $error+0;
											tableRow('Database',' Archivo "database.sql" disponible',1);
										}else{
											$error = $error+1;
											tableRow('Database',' Archivo "database.sql" disponible',0);
										}										
										?>
									</table>
								</div>
								<div class="item text-right">
									<?php
									if ($error==0) {
										echo '<a class="theme-button choto" href="?action=info">Siguiente paso <i class="fa fa-angle-double-right"></i></a>';
									}else{
										echo '<a class="theme-button btn-warning choto" href="?action=file">Revisar <i class="fa fa-sync-alt"></i></a>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}else {
				?>
				<div class="installation-wrapper pt-md-5">
					<ul class="installation-menu">
						<li class="steps running">
							<div class="thumb">
								<i class="fas fa-server"></i>
							</div>
							<h5 class="content">Requisitos<br>del servidor</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-file-signature"></i>
							</div>
							<h5 class="content">Permisos<br>de archivo</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-database"></i>
							</div>
							<h5 class="content">Información<br>de instalación</h5>
						</li>
						<li class="steps">
							<div class="thumb">
								<i class="fas fa-check-circle"></i>
							</div>
							<h5 class="content">Instalación<br>completada</h5>
						</li>
					</ul>
				</div>
				<div class="installation-wrapper">
					<div class="install-content-area">
						<div class="install-item">
							<h3 class="title text-center">Requisitos del servidor</h3>
							<div class="box-item">
								<div class="item table-area">
									<table class="requirment-table">
										<?php
										$error = 0;
										$phpversion = version_compare(PHP_VERSION, '8.1', '>=');
										if ($phpversion==true) {
											$error = $error+0;
											tableRow("PHP", "Se requiere PHP versión 8.1 o superior",1);
										}else{
											$error = $error+1;
											tableRow("PHP", "Se requiere PHP versión 8.1 o superior",0);
										}
										foreach ($requiredServerExtensions as $key) {
											$extension = isExtensionAvailable($key);
											if ($extension==true) {
												tableRow($key, "Extensión PHP ".strtoupper($key)." requerida",1);
											}else{
												$error += 1;
												tableRow($key, "Extensión PHP ".strtoupper($key)." requerida",0);
											}
										}
										?>
									</table>
								</div>
								<div class="item text-right">
									<?php
									if ($error==0) {
										echo '<a class="theme-button choto" href="?action=file">Siguiente paso <i class="fa fa-angle-double-right"></i></a>';
									}else{
										echo '<a class="theme-button btn-warning choto" href="?action=server">Revisar <i class="fa fa-sync-alt"></i></a>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<footer class="section-bg py-3 text-center">
		<div class="container">
			<p class="m-0 font-weight-bold">&copy;<?php echo Date('Y') ?> - <a href="#">DAL</a></p>
		</div>
	</footer>
	<style>
		#hide{
			display: none;
		}
	</style>
</body>
</html>