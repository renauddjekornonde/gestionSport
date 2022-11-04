<?php require_once("commande.php");
if(isset($_GET['action']) && $_GET['action'] == "deconnexion")
{
    session_destroy();
}

if(etudiantEstConnecter())
{
    header("location:accueil.php");
}

if(professeurEstConnecter())
{
    header("location:accueil.php");
}

if(internauteEstConnecterEtEstAdmin())
{
   
    header("location:accueil.php");
}

if($_POST)
{
    $resultat= executeRequete("SELECT * FROM Etudiant WHERE Mail='$_POST[Mail]'");
    $resultatProf= executeRequete("SELECT * FROM Professeur WHERE Mail='$_POST[Mail]'");
    $resultatAdmin= executeRequete("SELECT * FROM Administrateur WHERE Mail='$_POST[Mail]'");
    
    if($resultat->num_rows !=0)
    {
        $etudiant= $resultat->fetch_assoc();
        if($etudiant['Password']==$_POST['Password'])
        {
            foreach($etudiant as $indice => $element)
            {
                if($indice != 'Password')
                {
                    $_SESSION['etudiant'][$indice]= $element;
                }
            }
            header("location:accueil.php");
            
            
        }
        else
        {
			$contenu .="<script>alert('Mot de passe incorrect')</script>";
        }
    }
    elseif($resultatProf->num_rows !=0)
    {
        $professeur = $resultatProf->fetch_assoc();
        if($professeur['Password'] == $_POST['Password'])
        {
            foreach($professeur as $indiceProf => $elementProf)
            {
                if($indiceProf != 'Password')
                {
                    $_SESSION['professeur'][$indiceProf]= $elementProf;
                }
            }
            header("location:accueil.php");
            
            
        }
        else
        {
            $contenu .="<script>alert('Mot de passe incorrect')</script>";
        }
        
    }
    elseif($resultatAdmin->num_rows !=0)
    {
        $admin = $resultatAdmin->fetch_assoc();
        if($admin['Password'] == $_POST['Password'])
        {
            foreach($admin as $indiceAdmin => $elementAdmin)
            {
                if($indiceAdmin != 'Password')
                {
                    $_SESSION['administrateur'][$indiceAdmin]= $elementAdmin;
                }
            }
            header("location:accueil.php");
        }
        else
        {
            $contenu .="<script>alert('Mot de passe incorrect')</script>";
		}
        }
        
		else
		{
			$contenu .="<script>alert('Email incorrect')</script>";
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

	<title>Connexion</title>
</head>
<body>
	<div class="container">
		<?php echo $contenu; ?>
		<form action="" method="POST" class="login-email">
			<p class="login-text" style="font-size: 2rem; font-weight: 800;">Connexion</p>
			<div class="input-group">
				<input type="email" placeholder="Email" name="Mail" value="<?php //echo $mail; ?>" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Password" name="Password" value="<?php //echo $_POST['Password']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Connexion</button>
			</div>
			<p class="login-register-text">Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire ici</a>.</p>
		</form>
	</div>
</body>
</html>