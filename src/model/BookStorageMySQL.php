<?php


class BookStorageMySQL implements BookStorage
{
    private $db;

    /**
     * BookStorageMySQL constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function read($id)
    {
        // TODO: Implement read() method.
        $stmt= $this->db->prepare('SELECT * FROM books WHERE id= :id');
        $stmt->execute(array('id'=> $id));
        $result= $stmt->fetch();
        $book= new Book($result['name'], $result['image'], $result['account'], $result['description']);
        return $book;
    }

    public function readAll()
    {
        // TODO: Implement readAll() method.
        $stmt= $this->db->prepare('SELECT * FROM books');
        $stmt->execute();
        $result= $stmt->fetchAll();
        $data= array();
        foreach ($result as $key => $book){
            $data[$book['id']]= new Book($book['name'], $book['image'], $book['account'], $book['description']);
        }
        return $data;
    }

    public function create(Book $book)
    {
        // TODO: Implement create() method.
        $stmt= $this->db->prepare('INSERT INTO books (name, image, description, account) VALUES (:name, :image, :description, :account)');
        $stmt->execute(array('name'=> $this->noScript($book->getNom()), 'image'=> $this->noScript($book->getImage()), 'description'=> $this->noScript($book->getDescription()), 'account'=> $this->noScript($book->getCompte())));
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        $stmt= $this->db->prepare('DELETE FROM books WHERE id= :id');
        $stmt->execute(array('id'=> $id));
    }

    public function update($id, Book $obj)
    {
        // TODO: Implement update() method.
        $stmt= $this->db->prepare('UPDATE books SET name= :name, image= :image, description= :description WHERE id= :id');
        $stmt->execute(array('name'=> $this->noScript($obj->getNom()), 'image'=> $this->noScript($obj->getImage()), 'description'=> $this->noScript($obj->getDescription()), 'id'=> $id));
    }

    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }

    public function userBooks($idUser)
    {
        // TODO: Implement userBooks() method.
        $stmt= $this->db->prepare('SELECT * FROM books WHERE account= :idUser');
        $stmt->execute(array('idUser'=> $idUser));
        $result= $stmt->fetchAll();
        $data= array();
        foreach ($result as $key => $book){
            $data[$book['id']]= new Book($book['name'], $book['image'], $book['account'], $book['description']);
        }
        return $data;
    }
}