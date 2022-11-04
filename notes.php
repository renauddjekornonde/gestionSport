<?php
require_once("commande.php");
  if(etudiantEstConnecter())
  {
      $id= $_SESSION['etudiant']['ID_Etu'];
      $sport= $_SESSION['etudiant']['Nom_Sport'];
       
        $resultat =executeRequete("SELECT DISTINCT Notes.ID_Note, Notes.Nom_Etu, Notes.Type_Evaluation, Notes.Note FROM `Notes`, `Etudiant` where Etudiant.ID_Etu= $id AND Etudiant.Nom_Sport= '$sport' AND Notes.ID_Etu= $id");

        $contenu .= '<h2> Liste des Notes </h2>';
        $contenu .= 'Nombre d\'Evaluations :' . $resultat->num_rows;


    

        $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
        $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
        $contenu .= '<tr>';
        
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Nom complet</th>';
        $contenu .= '<th scope="col">Evaluation</th>';
        $contenu .= '<th scope="col">Note</th>';
        $contenu .= '</tr>';
        $contenu .='</thead>';
        while($ligne = $resultat->fetch_assoc())
        {
            $contenu .= '<tbody>';
            $contenu .= '<tr>';
            foreach($ligne as $indice => $information) 
            {
                if($indice == "Photo")
                {
                    $contenu .= '<td><img src="' . $information . '"="90" height="90"></td>';
                }
                else
                {
                    $contenu .= '<td>' . $information . '</td>';
                }
            }
            $contenu.= '</tr>';
            $contenu .= '</tbody>';
         }
        $contenu .= '</table></div><br><br>';
  }

  //----------------------------------------------------------//

  elseif(internauteEstConnecterEtEstAdmin())
  {
       
        $resultat =executeRequete("SELECT DISTINCT Notes.ID_Note, Notes.Nom_Etu, Notes.Type_Evaluation, Notes.Note, Departement.Nom_Departement FROM `Notes`, `Etudiant`, `Departement`, `Sport` GROUP BY Notes.ID_Note");

        $contenu .= '<h2> Liste des Notes </h2>';
        $contenu .= 'Nombre d\'Evaluations :' . $resultat->num_rows;


    

        $contenu .= ' <div class="container mt-5 d-flex justify-content-center">';
        $contenu .= '<table class="table  table-bordered float-right" ><thead  class="table-dark">';
        $contenu .= '<tr>';
        
        $contenu .= '<th scope="col">#</th>';
        $contenu .= '<th scope="col">Nom complet</th>';
        $contenu .= '<th scope="col">Evaluation</th>';
        $contenu .= '<th scope="col">Note</th>';
        $contenu .= '<th scope="col">Departement</th>';
        $contenu .= '</tr>';
        $contenu .='</thead>';
        while($ligne = $resultat->fetch_assoc())
        {
            $contenu .= '<tbody>';
            $contenu .= '<tr>';
            foreach($ligne as $indice => $information) 
            {
                if($indice == "Photo")
                {
                    $contenu .= '<td><img src="' . $information . '"="90" height="90"></td>';
                }
                else
                {
                    $contenu .= '<td>' . $information . '</td>';
                }
            }
            $contenu.= '</tr>';
            $contenu .= '</tbody>';
         }
        $contenu .= '</table></div><br><br>';
  }


  //-----------------------------------------------------------//
  else{header("location:connexion.php");}

  require_once("haider.php");
  echo $contenu;
  require_once("footer.php");
?>
