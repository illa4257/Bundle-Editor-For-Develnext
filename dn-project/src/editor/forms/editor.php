<?php
namespace editor\forms;

use std, gui, framework, editor;


class editor extends AbstractForm
{

    /**
     * @event textArea.keyUp 
     */
    function doTextAreaKeyUp(UXKeyEvent $e = null)
    {    
        $this->saveFile = true;
    }

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null)
    {    
        $this->script->callAsync();
    }


    public $file;
    public $saveFile = false;

    public function openFile($file){
        $this->file = $file;
        $this->textArea->text = file_get_contents($file);
    }

}
