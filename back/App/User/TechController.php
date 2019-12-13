<?php


namespace App\User;


use App\Models\UserModel;


Class TechController extends UserModel
{

    protected               $_tableName = "tech_list";
    protected               $_tableSchema = [
        [ 'id', 'int', 'required', 'primary', 'auto' ],
        [ 'name', 'char', 32, 'required' ],
        [ 'description', 'char', 120, 'required' ],
        [ 'username', 'char', 48, 'required' ],
        [ 'created_at', 'char', 24, 'required' ]
    ];


public function __construct()
    {
        parent::__construct();

        $_techList = $this->getTechList();
//            return false;

        if (count($_techList) < 1)
            $this->__generateTechList();
    }


public function getTechList()
    {
        if (($_rows = $this->getAll()) === false)
        {
            $this->isError(true) . PHP_EOL;
            $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError());
            return false;
        }

        return $_rows;
    }


public function getTechListJavascript()
    {
        $_techList = $this->getTechList();

        $_javascriptOut = "\t\t\tvar __techList = [];" . PHP_EOL;
        $_javascriptOut .= "\t\t\tvar __techDesc = [];" . PHP_EOL . PHP_EOL;

        if ($_techList === false)
            return "Error retrieving tech list";

        foreach ($_techList as $index=>$tech)
        {
            $_javascriptOut .= "\t\t\t__techList[$index] = \"{$tech['name']}\";" . PHP_EOL;
            $_javascriptOut .=  "\t\t\t__techDesc[$index] = \"{$tech['description']}\";" . PHP_EOL;
        }

        return $_javascriptOut . PHP_EOL;
    }


private function __generateTechList()
    {
        $_techList = Array(
            [
                'name' => 'c/c++',
                'description' => 'System programming languages',
                'username' => 'Admin',
                'created_at' => date("d/m/Y H:i:s")
            ],
            [
                'name' => 'JavaScript',
                'description' => 'The javaScript programming language',
                'username' => 'Admin',
                'created_at' => date("d/m/Y H:i:s")
            ],
            [
                'name' => 'php',
                'description' => 'The php language',
                'username' => 'Admin',
                'created_at' => date("d/m/Y H:i:s")
            ]
        );

        foreach ($_techList as $tech)
        {
            if ($this->insertTableRow($tech) === false)
            {
                $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError());
            }
        }

        if ($this->isError(true)) die();
        return true;
    }

}

