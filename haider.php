<?php require_once("commande.php");?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Activité Physique - SUPDECO </title>
        <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="<?php echo RACINE_SITE ; ?>css/styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="">🏆 BARA</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto py-4 py-lg-0">

                        <?php
                            if(internauteEstConnecterEtEstAdmin())
                            {
                            echo ' <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'gestion_etudiant.php">Etudiant</a></li>';
                            echo ' <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'gestion_professeur.php">Professeur</a></li>';

                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'notes.php">Notes</a></li>';

                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'absences.php">Absences</a></li>';

                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'calendrier.php">Calendrier</a></li>';
                            
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'connexion.php?action=deconnexion">Se Deconnecter</a></li>';
                            }

                            elseif(etudiantEstConnecter())
                            {
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'notes.php">Notes</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'absences.php">Absences</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'requete.php">Requete</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'calendrier.php">Calendrier</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'connexion.php?action=deconnexion">Se Deconnecter</a></li>';
                            }

                            elseif(professeurEstConnecter())
                            {
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'liste_etudiant.php">Liste</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'gestion_notes.php">Notes</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'gestion_absence.php">Absences</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'gestion_calendrier.php">Calendrier</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'connexion.php?action=deconnexion">Se Deconnecter</a></li>';
                            }

                            else
                            {
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'inscription.php">Inscription</a></li>';
                            echo '<li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="' . RACINE_SITE . 'connexion.php">Connexion</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('img/imageback.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1></h1>
                            <span class="subheading"></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
        <div class="container px-4 px-lg-5 ">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="post-preview">
                <p class="post-meta">
                    
