<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat de mensagens</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
	<div class="mt-5 container">
<?php 	
	try {
		$conn = new PDO("mysql:host=localhost;dbname=teste","root","123456");
		$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
			echo "Erro: ".$e->getMessage();
	}	
 ?>
 <form  method="POST" class="form-control">
 	<label for="nome" class="form-label">Insira seu nome</label><br>
 	<input type="text" name="nome" placeholder="Nome"><br><br>
 	<label for="msg" class="form-label">Mensagem</label><br>
 	<textarea name="msg" class="form-control" placeholder="Informe o texto"></textarea><br>
	<input type="submit" name="submit" class="btn btn-success form-control">	
 </form>

 <div class="mt-3 card">
 	<div class="card-header d-flex justify-content-between">
 		<h4>Chat</h4>
 		<form method="POST">
 			<input type="submit" name="clear" value="Limpar chat" class="btn btn-danger">
 		</form>
 	</div>
 	<div class="card-body">
 			
 <?php 	

 date_default_timezone_set("America/Sao_Paulo");
 // inserir as mensagems no banco
 if(isset($_POST['submit'])){
 	
 	$nome = addslashes(filter_var($_POST['nome'],FILTER_SANITIZE_SPECIAL_CHARS,FILTER_SANITIZE_STRING));
 	$msg = addslashes(filter_var($_POST['msg'],FILTER_SANITIZE_SPECIAL_CHARS));
 	$data_msg = date("Y/m/d H:i:s");
 	
 	if(!empty($nome) && !empty($msg)){
	 	$stmt = $conn->prepare("INSERT INTO teste.mensagem SET data_msg=?,nome_autor=?,msg=?");
	 	$stmt->execute(array($data_msg,$nome,$msg));
 	}
 }
 	$stmt= $conn->prepare("SELECT * FROM teste.mensagem ORDER BY data_msg DESC");
 	$stmt->execute();

 	if($stmt->rowCount()> 0){
	 	foreach ($stmt->fetchall() as $value): ?>
	 		<div class="shadow-none p-3 mb-5 bg-light rounded">
	 			<div class="d-flex justify-content-between">
	 				<p class="fw-bold"> <?php echo $value['nome_autor']; ?> </p>
	 				<p class=".fs-6 text fw-lighter text-muted"> <?php echo date('d/m/Y \à\s H:i:s',strtotime($value['data_msg'])); ?> </p>
	 			</div>
			  	<p class="lh-base"> <?php echo $value['msg']; ?> </p>
			  			
	 		</div>

<?php 	endforeach;

 	}else{ ?>
 		<div class="alert alert-warning" role="alert">
  			<p>Não há mensagens</p>
		</div>
 	<?php } ?>
	</div> <!-- FIM CARD-body -->
</div> <!-- FIM CARD -->
 <?php 	
 	// limpar todas as mensagens
 	if(isset($_POST['clear'])){
 		$stmt = $conn->prepare("DELETE FROM teste.mensagem"); //DELETA TUDO = DELETE SEM WHERE
 		if($stmt->execute()){
 			header("Refresh: 0"); //refresh na pag apos deletar
 		}
 	}
  ?>
  </div>
 </body>
</html>