<?php
namespace editor\forms;

use std, gui, framework, editor;


class editor extends AbstractForm
{

    /**
     * @event construct 
     */
    function doConstruct(UXEvent $e = null)
    {    
        $this->textArea->observer("text")->addListener(function ($old, $new){
            $this->updateWH($new);
            $this->tab->text = $this->text."*";
            $this->info->text = "Waiting...";
            $this->saveFile = true;
            $this->updateCode = true;
        });
        $this->textArea->observer("selection")->addListener(function ($old, $new){
            $this->updateSelection();
        });
        $this->script->callAsync();
    }

    
    function updateWH($new){
        $arr = str::lines($new);
        foreach ($arr as $one){
            $l = $this->textArea->font->calculateTextWidth($one."a");
            if($l>=$r){
                $r = $l;
            }
        }
        $r+=128;
        $this->textArea->width = $r;
        $this->code->width = $r+$this->code->data('offset');
        
        $height = arr::count($arr)*17;
        $height+=32;
        $this->textArea->height = $height+3;
        $this->code->height = $height;
    }
    
    function updateSelection(){
        $arr = [$this->getLineFromPos($this->textArea->text, $this->textArea->selection["start"])];
        $i = $arr[0];
        $end = $this->getLineFromPos($this->textArea->text, $this->textArea->selection["end"]);
        while($end!=$arr[0]){
            $i++;
            $arr[] = $i;
            $end--;
        }
        $this->code->selectedIndexes = $arr;
    }
    
    public $file;
    public $saveFile = false;
    public $updateCode = false;
    public $tab;
    public $text;

    public function openFile($file, $tab){
        $this->file = $file;
        $data = file_get_contents($file);
        $this->textArea->text = $data;
        $this->updateWH($data);
        $this->tab = $tab;
        $this->text = $tab->text;
        $this->updateCode = true;
        $tab->text .= ' (-)';
    }
    
    public function getText(){
        return $this->textArea->text;
    }
    
    function getLineFromPos($text, $pos){
        $text = str::lines($text);
        $ct = arr::count($text);
        $y = 0;
        while($y!=$ct and $pos>=0){
            $pos -= str::length($text[$y])+1;
            $y++;
        }
        return $y-1;
    }
    
}
