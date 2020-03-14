<?php


class Controller
{
    private $view;
    private $accountStorage;
    private $bookStorage;

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    public function __construct($view, $bookStorage, $accountStorage)
    {
        $this->view= new View();
        $this->view= $view;
        $this->bookStorage= $bookStorage;
        $this->accountStorage= $accountStorage;
    }

//    La fonction qui affiche un livre
    public function showInformation($id) {
        if($this->bookStorage->read($id)->getNom()!= null) $this->view->makeBookPage($this->bookStorage->read($id), $id, $this->verifyIsAccountBook($id));
        else $this->view->makeUnknownBookPage();
    }
//  fonction qui genere la page d'accueil
    public function acceuil(){
        $this->view->acceuil();
    }

    //    fonction qui genere la page d'erreur
    public function error(){
        $this->view->error();
    }

    //    fonction qui genere la page a propos
    public function aPropos(){
        $this->view->aPropos();
    }

    public function showList(){
        $this->view->makeListPage($this->bookStorage->readAll());
    }

    public function showMyList(){
        $router= new Router();
        if(!key_exists('user', $_SESSION)){
            header('Location: '. $router->getConnectionURL());
        }
        $this->view->makeListPage($this->bookStorage->userBooks($this->accountStorage->readByLogin($_SESSION['user'])['id']));
    }


//    la fonction qui s'occupe d'enregistrer un nouveau livre
    public function saveNewBook(){
        if(key_exists(NAME_REF, $_POST) || key_exists(DESCRIPTION_REF, $_POST) || key_exists(IMAGE_REF, $_POST) || key_exists(IMAGE_REF, $_FILES)){
            $data= array();
            $router= new Router();
            $data[NAME_REF]= key_exists(NAME_REF, $_POST)? $_POST[NAME_REF]: '';
            $data[IMAGE_REF]= (key_exists(IMAGE_REF, $_FILES))? $_FILES[IMAGE_REF]['name']: '';
            $data[DESCRIPTION_REF]= key_exists(DESCRIPTION_REF, $_POST)? $_POST[DESCRIPTION_REF]: '';
            if(!key_exists('user', $_SESSION)){
                header('Location: '. $router->getConnectionURL());
            }
            $data[ACCOUNT_REF]= (key_exists('user', $_SESSION) && !$this->accountStorage->checkLogin($_SESSION['user']))? $this->accountStorage->readByLogin($_SESSION['user'])['id']: '';

            $bookBuilder= new BookBuilder($data);
            if($bookBuilder->isValid()){

                $book= $bookBuilder->createBook();
                if($this->uploadImage($_FILES[IMAGE_REF])){
                    $this->bookStorage->create($book);
                    header('Location: '.$router->getBookURL($this->getId($data)));
                }
                else{
                    $this->view->makeBookCreationPage($data, $bookBuilder);
                }

            }
            else{
                $this->view->makeBookCreationPage($data, $bookBuilder);
            }
        }
        else{

        }

    }

//    fonction de confirmation de suppression
    public function bookAskDeletion($id){
        if($this->bookStorage->read($id) && key_exists('user', $_SESSION) && $this->verifyAccount($_SESSION['user'], $id)){
            $this->view->makeBookAskDeletionPage($this->bookStorage->read($id), $id);
        }else{
            $this->view->error();
        }
    }
//    fonction de suppression
    public function bookDeletion($id){
        $router= new Router();
        if($this->bookStorage->read($id) && $this->verifyAccount($_SESSION['user'], $id)){
            $this->bookStorage->delete($id);
            header('Location: '.$router->getBookList());
        }else{
            $this->view->error();
        }
    }

//    La fonction qui s'occupe de la modification du livre
    public function updateBookPage($id){
        $router= new Router();
        if(key_exists('user', $_SESSION) && !$this->verifyAccount($_SESSION['user'], $id)){
            header('Location: '. $router->getErrorURL());
        }
        if(key_exists('nom', $_POST) || key_exists('description', $_POST) || key_exists('image', $_POST)){
            if(key_exists('user', $_SESSION) && $this->verifyAccount($_SESSION['user'], $id)){
                $data[NAME_REF]= key_exists(NAME_REF, $_POST)? $_POST[NAME_REF]: '';
                $data[IMAGE_REF]= key_exists(IMAGE_REF, $_POST)? $_POST[IMAGE_REF]: '';
                $data[DESCRIPTION_REF]= key_exists(DESCRIPTION_REF, $_POST)? $_POST[DESCRIPTION_REF]: '';
                $data[ACCOUNT_REF]= $this->bookStorage->read($id)->getCompte();
                $bookBuilder= new BookBuilder($data);
                if($bookBuilder->isValid()){
                    $book= $bookBuilder->createBook();
                    if(key_exists(IMAGE_REF, $_FILES) && $_FILES[IMAGE_REF]['name']!== ''){
                        $is_uploaded= $this->uploadImage($_FILES[IMAGE_REF]);
                        if ($is_uploaded=== true){
                            $book->setImage($_FILES[IMAGE_REF]['name']);
                        }
                    }
                    $this->bookStorage->update($id, $book);
                    header('Location: '.$router->getBookURL($id));
                }
                else{
                    $this->view->makeBookUpdatePage($_POST, $bookBuilder, $id);
                }
            }
            else{
                header('Location: '. $router->getBookList());
            }

        }
        else{
            $book= $this->bookStorage->read($id);
            $data= array();
            $data[NAME_REF]= $book->getNom();
            $data[IMAGE_REF]= $book->getImage();
            $data[ACCOUNT_REF]= $book->getCompte();
            $data[DESCRIPTION_REF]= $book->getDescription();
            $bookBuilder= new BookBuilder($data);
            $this->view->makeBookUpdatePage($data, $bookBuilder, $id);
        }

    }

//    fonction de connection
    public function connection(){
        if(count($_POST)> 0  && isset($_POST['login']) && isset($_POST['mot_de_passe'])){
            if($this->accountStorage->checkAuth($_POST['login'], $_POST['mot_de_passe'])){
                $_SESSION['user']= $_POST['login'];
                $router= new Router();
                header('Location: '.$router->getBookList());
            }
            $this->view->makeConnectionFormWrongPage(new Account(null, $_POST['login'], $_POST['mot_de_passe'], null));
        }
        else
            $this->view->makeConnectionFormPage();
    }

//    fonction de creation de compte
    public function createAccount(){
        $data= array();
        $router= new Router();
        $data['login']= key_exists('login', $_POST)? $_POST['login']: "";
        $data['nom']= key_exists('nom', $_POST)? $_POST['nom']: "";
        $data['mot_de_passe']= key_exists('mot_de_passe', $_POST)? $_POST['mot_de_passe']: "";
        if(key_exists('login', $_POST) && key_exists('nom', $_POST) && key_exists('mot_de_passe', $_POST)){
            if ($this->getErrors($data)){
                if($this->accountStorage->checkLogin($data['login'])){
                    $this->accountStorage->create($data);
                    header('Location: '.$router->getBookList());
                }else{
                    $data['login']= "";
                    $this->view->makeAccountCreationPage($data, false);
                    return;
                }

            }
            else{
                $this->view->makeAccountCreationPage($data, false);
            }
        }else{
            $this->view->makeAccountCreationPage($data, true);
        }
    }

//    fonction deconnection
    public function deconnect(){
        session_destroy();
        $router= new Router();
        header('Location: '.$router->getHomeURL());
    }





    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }

    public function getId($data){
        foreach ($this->bookStorage->readAll() as $key => $item){
            if($data[NAME_REF]=== $item->getNom()  && $data[IMAGE_REF]=== $item->getImage()  && $data[DESCRIPTION_REF]=== $item->getDescription()  && $data[ACCOUNT_REF]=== $item->getCompte() ){
                return $key;
            }
        }
        return null;
    }

    private function getErrors($data){
        $errors= array();
        $r= true;
        foreach ($data as $key=> $error){
            if($error=== ""){
                $errors[$key]= true;
                $r= false;
            }
            else{
                $errors[$key]= false;
            }
        }
        return $r;
    }

//    verification de compte (couple id et login)
    public function verifyAccount($login, $idBook){
        if($this->accountStorage->readByLogin($login)['id']=== $this->bookStorage->read($idBook)->getCompte()){
            return true;
        }
        return false;
    }

//    verification de compte (couple id et login)
    public function verifyIsAccountBook($idBook){
        if(!key_exists('user', $_SESSION)) return false;
        if($this->accountStorage->readByLogin($_SESSION['user'])['id']=== $this->bookStorage->read($idBook)->getCompte()){
            return true;
        }
        return false;
    }

//    telecharger une image dans le serveur
    public function uploadImage($image){
        $router= new Router();
        $uploadfile = $router->getImagesPath() . $image['name'];
        if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
            return true;
        }
        return false;
    }

}
