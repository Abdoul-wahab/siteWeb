<?php

class Router
{
    public function main($bookStorage, $accountStorage){
        $view= new View();

        $control= new Controller($view, $bookStorage, $accountStorage);
        $path= '';
        if(isset($_SERVER['PATH_INFO'])){
            $path= explode('/', $_SERVER['PATH_INFO']);
        }
        $action = key_exists('action', $_GET)? $_GET['action']: null;
        $id= key_exists('id', $_GET)? $_GET['id']: null;

        if($id!==null && $action== null){
            $this->redirectToConnectionIfNotConnected();
            $control->showInformation($_GET["id"]);
        }
        elseif (key_exists('liste', $_GET)){
            $control->showList();
        }
        elseif (count($path)== 2){
            try{
                switch ($path[1]){
                    case 'nouveau':
                        $this->redirectToConnectionIfNotConnected();
                        $data= array();
                        $data[NAME_REF]= '';
                        $data[DESCRIPTION_REF]= '';
                        $data[IMAGE_REF]= '';
                        $data[ACCOUNT_REF]= '';
                        $bookBuilder= new BookBuilder($data);
                        $control->getView()->makeBookCreationPage($data, $bookBuilder);
                        break;

                    case 'liste':
                        $control->showList();
                        break;

                    case 'connection':
                        $this->redirectToErrorIfConnected();
                        $control->connection();
                        break;

                    case 'deconnection':
                        $this->redirectToConnectionIfNotConnected();
                        $control->deconnect();
                        break;

                    case 'sauverNouveau':
                        $this->redirectToConnectionIfNotConnected();
                        $control->saveNewBook();
                        break;

                    case 'creerCompte':
                        $this->redirectToErrorIfConnected();
                        $control->createAccount();
                        break;

                    case 'mesLivres':
                        $this->redirectToConnectionIfNotConnected();
                        $control->showMyList();
                        break;

                    case 'erreur':
                        $control->error();
                        break;

                    case 'aPropos':
                        $control->aPropos();
                        break;

                    default:
                        $this->redirectToConnectionIfNotConnected();
                        $control->showInformation($path[1]);
                        break;
                }
            }
            catch (Exception $e) {
                /* Si on arrive ici, il s'est passé quelque chose d'imprévu
                   * (par exemple un problème de base de données) */
//                $view->makeUnexpectedErrorPage($e);
            }
        }
        elseif (count($path)== 3){
            $this->chooseAction($control, $path[2], $path[1], $_POST);
        }
        elseif ($action!== null){
            $this->chooseAction($control, $action, $id, $_POST);
        }
        else{
            $control->acceuil();
        }


    }

    public function chooseAction($control, $action, $id, $post){
        try {
            switch ($action) {
                case "demanderSuppression":
                    $this->redirectToConnectionIfNotConnected();
                    $control->bookAskDeletion($id);
                    break;

                case "supprimer":
                    $this->redirectToConnectionIfNotConnected();
                    $control->bookDeletion($id);
                    break;

                case "modifier":
                    $this->redirectToConnectionIfNotConnected();
                    $control->updateBookPage($id);
                    break;

                case "test":
                    var_dump($_SERVER['REQUEST_METHOD']);
                    break;


                default:
                    /* L'internaute a demandé une action non prévue. */

                    break;
            }
        } catch (Exception $e) {
            /* Si on arrive ici, il s'est passé quelque chose d'imprévu
               * (par exemple un problème de base de données) */
//                $view->makeUnexpectedErrorPage($e);
        }
    }




//    Livres
    public function getHomeURL(){
        return DIRECTORY_NAME."/books.php";
    }

    public function getBookURL($id){
        return DIRECTORY_NAME."/books.php/$id";
    }

    public function getBookList(){
        return DIRECTORY_NAME."/books.php/liste";
    }

    public function getBookCreationURL(){
        return DIRECTORY_NAME."/books.php/nouveau";
    }

    public function getBookSaveURL(){
        return DIRECTORY_NAME."/books.php/sauverNouveau";
    }

    public function getBookUpdateURL($id){
        return DIRECTORY_NAME."/books.php/$id/modifier";
    }

    public function getBookUpdateFormURL($id){
        return DIRECTORY_NAME."/books.php/$id/modifier";
    }

    public function getBookAskDeletionURL($id){
        return DIRECTORY_NAME."/books.php/$id/demanderSuppression";
    }

    public function getBookDeletionURL($id){
        return DIRECTORY_NAME."/books.php/$id/supprimer";
    }

    public function getUserBooksURL(){
        return DIRECTORY_NAME."/books.php/mesLivres";
    }

//    compte

    public function getConnectionURL(){
        return DIRECTORY_NAME."/books.php/connection";
    }

    public function getDeconnectionURL(){
        return DIRECTORY_NAME."/books.php/deconnection";
    }

    public function getCreationAccountURL(){
        return DIRECTORY_NAME."/books.php/creerCompte";
    }

    public function getCSSURL(){
        return DIRECTORY_NAME."/src/style/book.css";
    }

    public function getImagesURL(){
        return DIRECTORY_NAME."/src/images/";
    }
    public function getImagesPath(){
        return "src/images/";
    }

    public function getErrorURL(){
        return DIRECTORY_NAME."/books.php/error";
    }

    public function getAProposrURL(){
        return DIRECTORY_NAME."/books.php/aPropos";
    }

    public function isConnected(){
        return key_exists('user', $_SESSION);
    }

    public function redirectToErrorIfConnected(){
        if ($this->isConnected()) header('Location: '.$this->getErrorURL());
    }

    public function redirectToConnectionIfNotConnected(){
        if (!$this->isConnected()) header('Location: '.$this->getConnectionURL());
    }

}