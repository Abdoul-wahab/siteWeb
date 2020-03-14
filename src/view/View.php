<?php


class View
{
    private $title;
    private $content;

    private $book;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->book= new Book('','', '', '');
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


    public function render(){
        $router= new Router();
        echo " 
        <html>
        <head>
            <title>$this->title</title>
            <meta charset=\"UTF-8\" />
	        <link rel=\"stylesheet\" href=\"".$router->getCSSURL()."\" />
        </head>
        <body>
        <header>
            <nav class='menu'>
                <ul class=\"navBar\">";

        if($router->isConnected()){
            echo "
            <li><a href='".$router->getHomeURL()."'>Accueil</a></li>
            <li><a href='".$router->getBookList()."'>Liste des livres</a></li>
            <li><a href='".$router->getBookCreationURL()."'>Ajouter un livre</a></li>
            <li><a href='".$router->getUserBooksURL()."'>Mes livres</a></li>
            <li><a href='".$router->getDeconnectionURL()."'>Deconnection</a></li>
            ";
        }else{
            echo "
            <li><a href='".$router->getHomeURL()."'>Accueil</a></li>
            <li><a href='".$router->getBookList()."'>Liste des livres</a></li>
            <li><a href='".$router->getConnectionURL()."'>Connexion</a></li>
            <li><a href='".$router->getCreationAccountURL()."'>Creer un compte</a></li>
            ";
        }

         echo "<li><a href='".$router->getAProposrURL()."'>A propos</a></li>
                </ul>
            </nav>
        </header>
        $this->content
        </body>
        </html>
        ";
    }

    public function makeTestPage(){
        echo "vurbuergber";
    }


    public function aPropos(){
        $this->title= "Page a propos";
        $this->content= "
        <h1>A propos</h1>
        <br>
                Ce site est un site d'echange de livres qui permet a un utilisateur de se connecter et proposer des livres aux autre utilisateurs</br>
                De base, on a voulu implementer une fonctionnalite qui permet a un utilisateur connecte de pouvoir commenter et proposer peut etre une echange ou un rachat en commentaire au proprietaire (mais on a pas insiste la dessus question d'organisation et de temps).</br></br>
                <em><strong>Autheurs: 21912512 et 21914280</strong></em>
        </p>
        ";
        $this->render();
    }



    public function makeBookPage($book, $id, $is_my_book){
        $this->book= $book;
        $this->title= $book->getNom();
        $router= new Router();
        $this->content= "
        <main>
		<h1>".$book->getNom()."</h1>
		<div class=\"bookPage\">
		    <img src=\"".$router->getImagesURL()."".$this->book->getImage()."\" alt=\"Image\" width=\"200px\" height=\"300px\">
		    <p>Description :</p><p class=\"desc\">".$this->book->getDescription()."</p>
        </div>";
        if($is_my_book){
            $this->content.="<p><a href='".$router->getBookUpdateFormURL($id)."'><button type='button'>Modifier</button></a><a href='".$router->getBookAskDeletionURL($id)."'><button type='button'>Supprimer</button></a></p>";
        }
        $this->content.="</main>
        ";
        $this->render();
    }

    public function makeUnknownBookPage(){
        $this->title= "Page inconnu";
        $this->content= "Page inconnu";
        $this->render();
    }

    public function acceuil(){
        $this->title= "Page d'acceuil";
        $this->content= "<h1 style='text-align: center; padding: 40px'>Bienvenue sur ce site d'echange de Livres</h1>";
        $this->render();
    }

    public function makeListPage($listBook){
        $this->title= "Tout les livres";
        $this->content= "<h1 style='text-align: center'>Liste des livres</h1>";
        $router= new Router();
        foreach ($listBook as $key => $book){
            $this->content.= "
<p style='margin-left: 40px'><img src=\"".$router->getImagesURL()."".$book->getImage()."\" alt=\"Image\" width=\"20px\" height=\"30px\"><a href='".$router->getBookURL($key)."'><span style='font-size: 20px; margin-left: 20px'>".$book->getNom()."</span></a></p>
";
        }
        $this->render();
    }

    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
        $this->render();
    }

    public function makeBookCreationPage($data, $bookBuilder){
        $this->title = 'Creation de livre';
        $router= new Router();

        $this->content="
        <form action='".$router->getBookSaveURL()."' method='post' enctype=\"multipart/form-data\" class='form-creation'>
        <div class='form-fields form-field-nom'>
        <label for='nom'>Nom: </label>
        <input type='text' name='".NAME_REF."' id='".NAME_REF."' value='".$data[NAME_REF]."'>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[NAME_REF]."
        </div>
        </div>
        <div class='form-fields form-field-description'>
        <label for='".DESCRIPTION_REF."'>Description: </label>
        <textarea class='text-area' name='".DESCRIPTION_REF."'>".$data[DESCRIPTION_REF]."</textarea>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[DESCRIPTION_REF]."
        </div>
        </div>
        <div class='form-fields form-field-image'>
        <label for='".IMAGE_REF."'>Image: </label>
        <input type='hidden' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
        <input type='file' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[IMAGE_REF]."
        </div>
        </div>
        <div class='form-fields form-field-submit'>
        <input type='submit' value='ajouter'>
        </div>
        </form>
        ";
        $this->render();
    }

    public function makeBookUpdatePage($data, $bookBuilder, $id){
        $this->title = 'Modification du livre';
        $router= new Router();
        $this->content="
        <form action='".$router->getBookUpdateURL($id)."' method='post' enctype=\"multipart/form-data\" class='form-creation'>
        <div class='form-fields form-field-nom'>
        <label for='nom'>Nom: </label>
        <input type='text' name='".NAME_REF."' id='".NAME_REF."' value='".$data[NAME_REF]."'>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[NAME_REF]."
        </div>
        </div>
        <div class='form-fields form-field-description'>
        <label for='".DESCRIPTION_REF."'>Description: </label>
        <textarea class='text-area' name='".DESCRIPTION_REF."'>".$data[DESCRIPTION_REF]."</textarea>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[DESCRIPTION_REF]."
        </div>
        </div>
        <div class='form-fields form-field-image'>
        <label for='".IMAGE_REF."'>Image: </label>
        <input type='hidden' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
        <input type='file' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$bookBuilder->getDataError()[IMAGE_REF]."
        </div>
        </div>
        <div class='form-fields form-field-submit'>
        <input type='submit' value='modifier'>
        </div>
        </form>
        ";
        $this->render();
    }

    public function error(){
        $this->title= "Page d'erreur";
        $this->content= "Erreur";
        $this->render();
    }

    public function makeBookAskDeletionPage($book, $id){
        $this->title= "Page confirmation de suppression";
        $router= new Router();
        $this->content= "
        <p>Vous etes sur de supprimer <a href='".$router->getBookURL($id)."'>".$book->getNom()."</a></p>
        <p><a href='".$router->getBookDeletionURL($id)."'><button type='button'>oui</button></a><a href='".$router->getBookURL($id)."'><button type='button'>non</button></a> </p>
        ";
        $this->render();
    }

    public function makeConnectionFormPage(){
        $router= new Router();
        $this->title= "Page connection";
        $this->content= "
        <form action='".$router->getConnectionURL()."' method='post' class='form-creation'>
        <div class='form-fields form-field-login'>
        <label for='login'>login: </label>
        <input type='text' name='login' id='login' />
        </div>
        <div class='form-fields form-field-espece'>
        <label for='mot_de_passe'>mot de passe: </label>
        <input type='password' name='mot_de_passe' id='mot_de_passe' />
        </div>
        <div class='form-fields form-field-submit'>
        <input type='submit' value='envoyer'>
        </div>
        </form>
        ";
        $this->render();
    }

    public function makeConnectionFormWrongPage(Account $account){
        $router= new Router();
        $this->title= "Page connection";
        $this->content= "
        <form action='".$router->getConnectionURL()."' method='post' class='form-creation'>
        <div class='form-fields form-field-login'>
        <label for='login'>login: </label>
        <input type='text' name='login' id='login' value='".$account->getLogin()."' />
        </div>
        <div class='form-fields form-field-espece'>
        <label for='mot_de_passe'>mot de passe: </label>
        <input type='password' name='mot_de_passe' id='mot_de_passe' value='".$account->getMotDePasse()."' />
        </div>
        <div class='form-fields form-error' style='color: #ff4c1e'>
        login ou mot de passe incorrecte
        </div>
        <div class='form-fields form-field-submit'>
        <input type='submit' value='envoyer'>
        </div>
        </form>
        ";
        $this->render();
    }

    public function makeAccountCreationPage($data, $b){
        $router= new Router();
        $errors= $this->getErrors($data, $b);
        $this->title= "Page de creation de compte";
        $this->content= "
        <form action='".$router->getCreationAccountURL()."' method='post' class='form-creation'>
        <div class='form-fields form-field-login'>
        <label for='login'>login: </label>
        <input type='text' name='login' id='login' value='".$data['login']."'  />
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$errors['login']."
        </div>
        </div>
        <div class='form-fields form-field-nom'>
        <label for='login'>Nom: </label>
        <input type='text' name='nom' id='nom' value='".$data['nom']."' />
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$errors['nom']."
        </div>
        </div>
        <div class='form-fields form-field-espece'>
        <label for='mot_de_passe'>mot de passe: </label>
        <input type='password' name='mot_de_passe' id='mot_de_passe' value='".$data['mot_de_passe']."' />
        <div class='form-fields form-error' style='color: #ff4c1e'>
        ".$errors['mot_de_passe']."
        </div>
        </div>
        <div class='form-fields form-field-submit'>
        <input type='submit' value='envoyer'>
        </div>
        </form>
        ";
        $this->render();
    }

    private function getErrors($data, $b){
        $errors= array();
        foreach ($data as $key=> $error){
            if($error=== "" && $b=== false){
                $errors[$key]= "valeur non valide";
            }
            else{
                $errors[$key]= "";
            }
        }
        return $errors;
    }

}