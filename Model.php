<?php
class Model
{ /*
 I modellen hämtas all data från databastabellerna och skickas sedan vidare in i Controllern

 */

    private $pdoCon;

    private function getPDO()
    { //privat funktion för att öppna databaskoppling
        $username = 'db30';
        $password = 'FJJAcyMU';
        $dsn = 'mysql:host=utb-mysql.du.se;dbname=db30';

        $this->pdoCon = new PDO($dsn, $username, $password);
        return $this->pdoCon;
    }

    private function closePDO()
    { //privat funktion för att stänga databaskoppling
        $this->pdoCon = null;
    }

    public function getAllThoughts(){ //I denna SQL-fråga sorteras tankarna på datum
        $pdoStatement = $this->getPDO()->prepare('CALL h15aspto_getAllThoughts()');
        $pdoStatement->execute();
        $thgtArray = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $thgtArray;
        $this->closePDO();
    }

    public function addThought(){ //metoden lägger till en tanke i databasen med hjälp av post-variabler som namnges i template1.html
        try {

            $pdoStatement = $this->getPDO()->prepare('INSERT INTO h15aspto_tankar (rubrik, tanke, likes, dislikes, datum) VALUES (:rubriken, :tanken, 0, 0, CURRENT_TIMESTAMP)');

           //metoden bindParam skapar platshållare till vår SQL-sats ovan. $_POST-variablerna namnges i template1.html
            $pdoStatement->bindParam(':rubriken', filter_var($_POST['rubrik'], FILTER_SANITIZE_STRING));
            $pdoStatement->bindParam(':tanken', filter_var($_POST['tanke'], FILTER_SANITIZE_STRING));
            $pdoStatement->execute();
            $this->closePDO();
        } catch (Exception $ex) { //kastar undantag om något går fel
            $this->pdocon = NULL;
            throw new Exception('P.g.a. fel i databas kan produkten ej uppdateras');
        }
    }

    public function like(){ //anropar stoed procedure som inkrementerar antalet likes med 1
        $pdoStatement = $this->getPDO()->prepare("CALL h15aspto_like(:idt)");
        $pdoStatement->bindParam(':idt', filter_var($_POST['id'], FILTER_SANITIZE_STRING));
        $pdoStatement->execute();
        $this->closePDO();
    }

    public function dislike(){ //anropar stoed procedure som inkrementerar antalet dislikes med 1
        $pdoStatement = $this->getPDO()->prepare("CALL h15aspto_dislike(:idt)");
        $pdoStatement->bindParam(':idt', filter_var($_POST['id'], FILTER_SANITIZE_STRING));
        $pdoStatement->execute();
        $this->closePDO();
    }

    public function getAllComments(){ //I denna SQL-fråga sorteras kommentarerna på datum
        $pdoStatement = $this->getPDO()->prepare('CALL h15aspto_getAllComments()');
        $pdoStatement->execute();
        $comArray = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $comArray;
        $this->closePDO();
    }

    public function addComment(){ //Denna metod lägger till en kommentar med hjälp av postat data och platshållare, på samma sätt som i addThought
        try {

            $pdoStatement = $this->getPDO()->prepare('INSERT INTO h15aspto_kommentarer (tankeid, kommentar, datum) VALUES (:tankeidt, :kommentaren, CURRENT_TIMESTAMP)');
            $pdoStatement->bindParam(':tankeidt', filter_var($_POST['tankeid'], FILTER_SANITIZE_STRING));
            $pdoStatement->bindParam(':kommentaren', filter_var($_POST['kommentar'], FILTER_SANITIZE_STRING));
            $pdoStatement->execute();
            $this->closePDO();
        } catch (Exception $ex) { //kastar undantag om något går fel
            $this->pdocon = NULL;
            throw new Exception('P.g.a. fel i databas kan produkten ej uppdateras');
        }
    }






}
?>