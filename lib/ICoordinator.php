<?php
/**
 * ICoordinator.php
 *
 * @package axiles89\coordinator
 * @date: 23.03.2016 20:34
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\coordinator;


/**
 * Interface ICoordinator
 * @package axiles89\coordinator
 */
interface ICoordinator
{
    /**
     * @param array $data
     * @return mixed
     */
    public function execute(array $data);
}