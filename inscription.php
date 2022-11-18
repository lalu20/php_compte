<?php
//------Require de init.php
require_once "inc/init.php";

//------ etape 2 Traitement des données du formulaire. !!!!!je vérifie si le formulaire a été validé
// S'il a été validé, je peux traiter les données.
//ATTENTION! Je  ne peux pas traiter les données  si le formulaire 

if (!empty($_POST)) {
    debug($_POST);

    //ETAPE de vérification des données: je vérifie si je n'ai pas de message d'erreur.
    //Si $errorMessage est vide alors les données envoyés par l'utilisateur sont correctes.donc, on peu envoyer
    if (empty($_POST['username'])) {
        $errorMessage = "Merci d'indiquer un Pseudo <br>";
    } //strlen permet de récup la longueur de chaine de caractère. ATTANTION la caractères spéciaux comptent pour 2.
    //exemple: éé comptera pour 4 caractères.
    //iconv_strlen() permet de résoudre ce problème. Chaque carc comptera comme 1 ex: éé compte pour 2 catrac.

    if (
        iconv_strlen(trim($_POST['username'])) < 3 ||
        iconv_strlen(trim($_POST['username'])) > 20
    ) {
        $errorMessage .= "Le Pseudo doit contenir entre 3 et 20 caractères <br>";

        if (empty($_POST['password']) || iconv_strlen(trim($_POST['password'])) < 8) {
            $errorMessage .= "Merci de rentrer un mot de passe minimum 8 caractères <br>";
        }
        if (empty($_POST['lastname']) || iconv_strlen(trim($_POST['lastname'])) > 70) {
            $errorMessage .= "Merci de rentrer un nom maximum 70 caractères <br>";
        }
        if (empty($_POST['firstname']) || iconv_strlen(trim($_POST['firstname']) > 70)) {
            $errorMessage .= "Merci de rentrer un prénom maximum 70 caractères <br>";
        }
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessage .= "l'émail n'est pas valide <br>";
        }
    }


    //------------------------------- ETAPE DE SECURISATION DE DONNEES---------------
    //xml est une liste de données en forme de balise html. pour eviter les injections sql
    // $_POST['username']=htmlspecialchars($_POST['username']);
    // $_POST['lastname']=htmlspecialchars($_POST['lastname']);
    // $_POST['firstname']=htmlspecialchars($_POST['firstname']);

    foreach ($_POST as $key => $value){
        $_POST[$key]= htmlspecialchars($value, ENT_QUOTES);
    }


    //-------Etape envoi de donnees

    if (empty($errorMessage)) {
        $requete = $bdd->prepare("INSERT INTO membre VALUES(NULL, :username, :password, :lastname, :firstname, :email, :status)");
        $success = $requete->execute([
            ":username" => $_POST['username'],
            ":password" => password_hash($_POST['password'], PASSWORD_DEFAULT), // password_hash()permet de hasher un MDP. On doit
            // lui indiquer en param le type d'algo que l'on souhaite utiliser. ici on prend l'algo par defaut.
            ":lastname" => $_POST['lastname'],
            ":firstname" => $_POST['firstname'],
            ":email" => $_POST['email'],
            ":status" => "user"
        ]);

        if ($success) {
            $successMessage = "Inscription reussie";
            // si ma requete fonctionne je serai redirigé vers la page de connexion.
            header("location:connexion.php");//la fonction header() est tjrs suivie par exit;
            exit;// permet d'accélérer l'exécution du code et ça sécurise la page
        } else {
            $errorMessage = "Erreur d'inscription";
        }
    }



    //------FIN Envoi des données

}

require_once "inc/header.php"; ?>

<h1 class="text-center">Inscription</h1>

<?php if (!empty($successMessage)) { ?>
    <div class="alert alert-success col-md-6 text-center mx-auto">
        <?php echo $successMessage ?>
    </div>

<?php } ?>

<?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-danger col-md-6 text-center mx-auto">
        <?php echo $errorMessage ?>
    </div>

<?php } ?>

<form action="" method="post" class="col-md-6 mx-auto">

    <label for="username" class="form-label">Pseudo</label>
    <input type="text" name="username" id="username" class="form-control" value="<?php echo $_POST['username'] ?? "" ?>" value="<?= $_POST['username'] ?? "" ?>">
    <!----echo $_POST['username'] ?? "" ?>" existe alors j'affiche sa valeur SINON j'affiche une chaine de caractère vide. On utilise ici l'opérateur NULL COALISCENT ??--->
    <div class="invalid-feedback"></div>

    <label for="password" class="form-label">Mot de Passe</label>
    <input type="password" name="password" id="password" class="form-control">
    <div class="invalid-feedback"></div>

    <label for="lastname" class="form-label">Nom</label>
    <input type="text" name="lastname" id="lastname" class="form-control" value="<?= $_POST['lastname'] ?? "" ?>">
    <div class="invalid-feedback"></div>

    <label for="firstname" class="form-label">Prénom</label>
    <input type="text" name="firstname" id="firstname" class="form-control" value="<?= $_POST['firstname'] ?? "" ?>">
    <div class="invalid-feedback"></div>

    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="<?= $_POST['email'] ?? "" ?>">
    <div class="invalid-feedback"></div>
    <button class="btn btn-success d-block mx-auto mt-3">S'inscrire</button>

</form>

<?php
require_once "inc/footer.php";
