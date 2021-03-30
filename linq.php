<?php
class DataObject
{
    private $workingArray;
    private $returnArray;

    function __construct($inputArray)
    {
        $this->workingArray = $inputArray;
    }
    function where($whereFunction)
    {
        foreach($this->workingArray as $arrayValue)
        {
            if ($whereFunction($arrayValue))
            {
                $this->returnArray = $arrayValue;
            }
        }
        return $this;
    }
    function select($query = NULL)
    {
        if ($query == NULL)
            if (is_array($this->returnArray))
                return reset($this->returnArray);
            else
                return $this->returnArray;
        else
        {
            foreach($this->returnArray as $keyValue => $arrayValue)
            {
                if ($keyValue == $query)
                {
                    return $arrayValue;
                }
            }
        }
    }
}
function from ($inputArray)
{
    return new DataObject($inputArray);
}

?>