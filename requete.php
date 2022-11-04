<?php
require_once("commande.php");
if(etudiantEstConnecter())
{
    if(!empty($_POST))
    {
        // affichage des saisies pour etre de les obtenir avant de les exploiter.
        echo "sujet : $_POST[sujet] <br>";
        echo "message : $_POST[message] <br>";
        echo "expediteur : $_POST[expediteur] <br>";

        // entete email
        $headers = 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=ISO-8859-1'."\n";
        $headers .= 'Reply-To:' .$_POST['expediteur'] . "\n";

        $headers = 'From: "' . ucfirst(substr($_POST['expediteur'], 0, strpos($_POST['expediteur'], '@') )) .'"<'.$_POST['expediteur'].'>' . "\n";

        $headers .='Delivered-to: djekornonderenaud@gmail.com' . "\n";

        mail("djekornonderenaud@gmail.com", $_POST['sujet'], $_POST['message'], $headers);

    }
}
else
{
    header("location:connexion.php");
}
?>
<?php require_once("haider.php")?>
<h1>REQUETE</h1><br>
<from method="post" action="" class="w-70">
        <input type="email" class="form-control" name="expediteur" id="expediteur" placeholder="Expediteur"><br><br>
        <input type="text"class="form-control" name="sujet" id="sujet" placeholder="Sujet"><br><br>
        <textarea class="form-control" name="messaget" placeholder="Message"> </textarea><br><br>
        <input type="submit" class="btn btn-primary" value="Envoyer la requete">
    </from>
<?php require_once("footer.php");?>