<?php

/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 6/9/16
 * Time: 5:57 PM
 */
class abstractAdminTest extends PHPUnit_Framework_TestCase
{
    public function testIfResponseIsSentOnlyOneTime()
    {
        $stub = $this->getMockForAbstractClass('\\wMVC\\Controllers\\abstractAdmin');
        ob_get_clean();
        print 'nggaz';
    }
}
