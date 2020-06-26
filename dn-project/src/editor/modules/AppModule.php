<?php
namespace editor\modules;

use std, gui, framework, editor, scripts\updater;


class AppModule extends AbstractModule
{
    /**
     * @event action 
     */
    function doAction(ScriptEvent $e = null)
    {    
        var_dump($GLOBALS);
        $GLOBALS['version'] = "dev-v0.0.1.1";
        $GLOBALS['progdir'] = fs::parent($GLOBALS['argv'][0]) . '/';
        $GLOBALS['progdir'] = "";
        $GLOBALS['projectdir'] = fs::abs('./') . '/project/';
        $GLOBALS['nickname'] = 'illa4257';
        $GLOBALS['repo'] = 'Bundle-Editor-for-Develnext';
        $GLOBALS['updater'] = new updater;
    }
}
