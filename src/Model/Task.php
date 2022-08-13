<?php

namespace App\Model;
// не указано ни одного геттера
class Task implements \JsonSerializable
{
    //добавить справочник статусов
    public const IN_PROGRESS_STATUS = 'IN_PROGRESS';
    public const COMPLETED_STATUS = 'COMPLETED';
    public const FAILED_STATUS = 'FAILED';

    /**
     * @var array
     */
    private $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        // по README здесь должно быть ['id','title]
        return $this->_data;
    }
}
