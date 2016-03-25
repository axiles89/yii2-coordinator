<?php
/**
 * ICoordinatorComponent.php
 *
 * @package axiles89\coordinator
 * @date: 25.03.2016 20:11
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\coordinator;


/**
 * Interface ICoordinatorComponent
 * @package axiles89\coordinator
 */
interface ICoordinatorComponent
{
    /**
     * @param array $db
     * @param $data
     * @return mixed
     */
    public function getShard(array $db, $data);
}