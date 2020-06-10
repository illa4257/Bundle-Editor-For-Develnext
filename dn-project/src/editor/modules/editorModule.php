<?php
namespace editor\modules;

use std, gui, framework, editor;


class editorModule extends AbstractModule
{


    /**
     * @event script.action 
     */
    function doScriptAction(ScriptEvent $e = null)
    {    
        while(true){
            if($this->getContextForm()->saveFile){
                $this->getContextForm()->saveFile = false;
                file_put_contents($this->getContextForm()->file, $this->textArea->text);
            }
            sleep(5);
        }
    }

}
