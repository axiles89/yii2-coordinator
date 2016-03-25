<?php
/**
 * CoordinatorComponent.php
 *
 * @package axiles89\coordinator
 * @date: 23.03.2016 19:35
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\coordinator;


use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class CoordinatorComponent
 * @package axiles89\coordinator
 */
class CoordinatorComponent extends Component implements ICoordinatorComponent
{
    /**
     * @var array
     */
    public $component = [];
    /**
     * @var string
     */
    public $prefix = "db";
    /**
     * @var array
     */
    private $coordinator = [];

    /**
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();

        if (!$this->component) {
            throw new InvalidConfigException("Please set component for coordinator");
        }

        foreach ($this->component as $value) {
            $coordinator = \Yii::createObject($value);

            if (!$coordinator instanceof ICoordinator) {
                throw new InvalidConfigException("Component coordinator not implements ICoordinator interface.");
            }

            $this->coordinator[] = $coordinator;
        }
    }

    /**
     * Get shard
     * @param $db - array db name component
     * @param $data - key shard
     * @return array
     * @throws InvalidConfigException
     */
    public function getShard(array $db, $data) {
        $data = (!is_array($data)) ? [$data] : $data;

        if (!$data) {
            return [];
        }

        $number = [];
        $params = $data;

        foreach ($this->coordinator as $coordinator) {
            $params = array_merge($number, $coordinator->execute($params));
        }

        if (!$params) {
            return [];
        }

        $result = array_map(function(&$value) {
            return $this->prefix.$value;
        }, $params);

        $error = array_diff($result, $db);

        if ($error) {
            throw new InvalidConfigException("Not found shard db in db project");
        }

        return (count($result) == 1) ? $result[0] : $result;
    }
}