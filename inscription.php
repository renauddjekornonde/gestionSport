<?php require_once("commande.php");
if($_POST)
{
    debug($_POST);
	if($_POST['Password']==$_POST['cpassword'])
	{
		$etudiant= executeRequete("SELECT * FROM Etudiant WHERE Mail='$_POST[Mail]'");
		if($etudiant->num_rows>0)
		{
			$contenu .= "<script>alert('Ce compte mail exite déja veuillez choisir un autre!.')</script>";
		}
		else
		{

			$photo_bdd= $_FILES['Photo'];
        if(!empty($_FILES['Photo']['name']))
        {
            $nom_photo= $_POST['Nom_Etu'] . '_' .$_FILES['Photo']['name'];
            $photo_bdd= RACINE_SITE . "Photo/$nom_photo";
            $photo_dossier= $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . "Photo/$nom_photo";
            copy($_FILES['Photo']['tmp_name'], $photo_dossier);
        }
			foreach($_POST as $indice=>$valeur)
			{
				$_POST[$indice]= htmlEntities(addSlashes($valeur));
			}
			executeRequete("INSERT INTO Etudiant (Mail, Date_Naissance, Password, Photo, Nom_Departement, Nom_Sport, Nom_Etu) VALUES ('$_POST[Mail]','$_POST[Date_Naissance]','$_POST[Password]', '$photo_bdd', '$_POST[Nom_Departement]','$_POST[Nom_Sport]', '$_POST[Nom_Etu]')");
	
			$contenu.= "<div class='alert alert-sucessfully'>Inscription réussite. <a href=\"Connexion.php\"><u>Cliquez ici pour vous connecter</u></a></div>";
		}
	}
	else
	{
		$contenu .="<script>alert('Mot de passe non confirmer.')</script>";
	}
}
   
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="style.css">

	<title>inscription</title>
</head>
<body>
	<div class="container">
		<?php echo $contenu;?>
		<form action="" method="POST" class="login-email"  enctype="multipart/form-data">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">inscription</p>

			<div class="input-group">
				<input type="text" placeholder="Nom complet" name="Nom_Etu" required>
			</div>

			<div class="input-group">
				<input type="email" placeholder="Email" name="Mail"  required>
			</div>

			<div class="input-group">
				<input type="password" placeholder="Password" name="Password"  required>
            </div>

            <div class="input-group">
				<input type="password" placeholder="Confirm Password" name="cpassword"  required>
			</div>

				<select class="form-select form-select-sm" aria-label=".form-select-sm" name="Nom_Departement"  placeholder="Departement" >
					<option value="ESITEC">ESITEC</option>
					<option value="IMTN">IMTN</option>
					<option value="MERCURE">MERCURE</option>
					<option value="EPITA">EPITA</option>
				</select>

				<select class="form-select form-select-sm" aria-label=".form-select-sm" name="Nom_Sport" placeholder="Sport" >
					<option value="FITNESS">FITNESS</option>
					<option value="FOOTBALL">FOOTBALL</option>
					<option value="BASKET">BASKET</option>
					<option value="TENNIS">TENNIS</option>
				</select> <br><br>

			<div class="input-group">
				<input type="date" placeholder="Date de Naissance" name="Date_Naissance" required>
			</div>

			<div class="input-group">
				<input type="file" placeholder="Photo" name="Photo"  required>
			</div>

			<div class="input-group">
				<button name="submit" class="btn">s'inscire</button>
			</div>
			<p class="login-register-text">Vous avez deja un compte? <a href="connexion.php">Connectez vous</a>.</p>
		</form>
	</div>
</body>
</html>