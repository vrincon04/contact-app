<?php


namespace App\Enums;


abstract class Enum
{
    private static $constCacheArray = NULL;
    protected $currValue = null;
    protected $currName = null;

    private static function getConstants(): array
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    /**
     * @param mixed $value
     * @throws \Exception
     * @todo  wait to real implementation of the enum
     */
    public function __construct($value) {
        if(self::isValidValue($value)){
            $this->currValue = $value;
            $myConsts = self::getConstants();
            $this->currName = array_search($this->currValue, $myConsts);
        }else{
            throw new \Exception('Not valid value '.$value, 500);
        }
    }

    /**
     * getValue description]
     * @return mixed departs of the class
     * @todo  wait to real implementation of the enum
     */
    public function getValue(){
        return $this->currValue;
    }
    /**
     * @param mixed $name
     * @param Bool $strict
     * @return Bool
     */
    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        foreach ($constants as $key => $value) {
            $key = strtolower(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $key));
            if($name == $key){
                return $value;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return Bool
     */
    public static function isValidValue($value) {
        $values = array_values(self::getConstants());
        return in_array($value, $values);
    }

    /**
     * @param  $beautify represents if the query is going to be
     *                   beautified or not
     * @return String
     */
    public function getInternalName($beautify = false) {
        if($beautify){
            return strtolower(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->currName));
        }
        return $this->currName;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'   => $this->currValue,
            'name' => $this->currName
        ];
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getAll() {
        $calledClass = get_called_class();
        $reflect = new \ReflectionClass($calledClass);
        $constants = $reflect->getConstants();
        $listConstants = array();

        foreach ($constants as $constantKey => $constantValue) {
            $listConstants[] = new $calledClass($constantValue);
        }

        return $listConstants;
    }
}
