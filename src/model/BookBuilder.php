<?php

define("NAME_REF", "nom");
define("IMAGE_REF", "image");
define("ACCOUNT_REF", "compte");
define("DESCRIPTION_REF", "description");
class BookBuilder
{

    private $data= array(), $error= null, $data_error= array(NAME_REF=> null, IMAGE_REF=> null, ACCOUNT_REF=> null, DESCRIPTION_REF=> null);

    /**
     * BookBuilder constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param null $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function getDataError()
    {
        return $this->data_error;
    }

    /**
     * @param array $data_error
     */
    public function setDataError(array $data_error)
    {
        $this->data_error = $data_error;
    }


//  creer un Livre a partir de builder
    public function createBook(){
        $animal= new Book($this->data[NAME_REF], $this->data[IMAGE_REF], $this->data[ACCOUNT_REF], $this->data[DESCRIPTION_REF]);
        return $animal;
    }

    public function isValid(){
        $nom= key_exists(NAME_REF, $this->data)? $this->data[NAME_REF]: "";
        $image= key_exists(IMAGE_REF, $this->data)? $this->data[IMAGE_REF]: "";
        $compte= key_exists(ACCOUNT_REF, $this->data)? $this->data[ACCOUNT_REF]: "";
        $description= key_exists(DESCRIPTION_REF, $this->data)? $this->data[DESCRIPTION_REF]: "";
        if($nom!== "" && $image!== "" && $compte!== "" && $compte>= 0 && is_numeric($compte) && $description!== ""){
            return true;
        }
        else{
            $this->data_error[NAME_REF]= ($nom=== "")? "Nom Invalide": null;
            $this->data_error[IMAGE_REF]= ($image=== "")? "Image Invalide": null;
            $this->data_error[ACCOUNT_REF]= ($compte=== ""|| $compte< 0 || !is_numeric($compte))? "Compte Invalide": null;
            $this->data_error[DESCRIPTION_REF]= ($description=== "")? "description Invalide": null;
            return false;
        }
    }

    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }
}