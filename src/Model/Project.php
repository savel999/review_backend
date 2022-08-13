<?php

namespace App\Model;
// в моделях Project/Task я бы отказался от data и завел бы свойства класса по колонкам из БД
// не хватает геттеров getTitle,getCreatedAt
class Project //упущено implements \JsonSerializable
{
    /**
     * @var array
     */
    public $_data;//private
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return int
     */
    public function getId()//public function getId():int
    {
        return (int) $this->_data['id'];
    }

    /**
     * лучше реализовать метод jsonSerialize()
     * @return string
     */
    public function toJson()//public function jsonSerialize()
    {
        return json_encode($this->_data);
    }
}
