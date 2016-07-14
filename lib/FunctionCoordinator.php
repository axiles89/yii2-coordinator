<?php
/**
 * FunctionCoordinator.php
 *
 * @package axiles89\coordinator
 * @date: 23.03.2016 20:36
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\coordinator;

use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class FunctionCoordinator
 * @package axiles89\coordinator
 */
class FunctionCoordinator extends Component implements ICoordinator
{
    /**
     * @var
     */
    public $function;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->function or !is_callable($this->function)) {
            throw new InvalidConfigException('Please set callable function for coordinator component');
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data)
    {
        $result = [];

        foreach ($data as $value) {
            $result[] = call_user_func($this->function, $value);
        }

        return array_unique($result);
    }
}