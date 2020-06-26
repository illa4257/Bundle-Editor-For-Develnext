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
                file_put_contents($this->getContextForm()->file, $this->getContextForm()->getText());
                uiLaterAndWait(function (){
                    $this->info->text = "Saved!";
                    $this->getContextForm()->tab->text = $this->getContextForm()->text;
                });
            }
            if($this->getContextForm()->updateCode){
                $this->getContextForm()->updateCode = false;
                $text = str::lines($this->getContextForm()->getText());
                $ct = arr::count($text);
                $code = $this->code->items;
                $cc = $code->count();
                
                // Lines
                while($ct!=$cc){
                    if($ct>=$cc){
                        $cc++;
                        $UXPanel = new UXPanel;
                        $UXPanel->borderWidth = 0;
                        $c = new UXLabelEx($cc);
                        $UXPanel->children->add($c);
                        uiLaterAndWait(function () use ($code, $UXPanel){
                            $code->add($UXPanel);
                        });
                    }else{
                        $cc--;
                        uiLaterAndWait(function () use ($code, $cc){
                            $code->removeByIndex($cc);
                        });
                    }
                }
                
                // Colored text (y)
                $y = 0;
                $oldWidth = $gwidth;
                $gwidth = 0;
                while($y!=$ct){
                    // x
                    $posx = 0;
                    $line = str::lines("\n".str::replace($text[$y], ' ', "\n \n"));
                    $cl = arr::count($line);
                    $obj = $code->offsetGet($y)->children;
                    $cc = $obj->count();
                    while($cl!=$cc){
                        if($cl>=$cc){
                            $cc++;
                            $UXLabelEx = new UXLabelEx;
                            uiLaterAndWait(function () use ($obj, $UXLabelEx){
                                $obj->add($UXLabelEx);
                            });
                        }else{
                            $cc--;
                            uiLaterAndWait(function () use ($obj, $cc){
                            $obj->removeByIndex($cc);
                        });
                        }
                    }
                    
                    // Colored text (x)
                    $x = 0;
                    while($x!=$cl){
                        if($x!=0){
                            $UXLabelEx = $obj->offsetGet($x);
                            $value = $line[$x];
                            uiLaterAndWait(function () use ($UXLabelEx, $value, $posx){
                                $UXLabelEx->text = $value;
                                $UXLabelEx->x = $posx;
                            });
                            $width = $UXLabelEx->font->calculateTextWidth($value);
                            $posx += $width;
                        }else{
                            $UXLabelEx = $obj->offsetGet($x);
                            $width = $UXLabelEx->font->calculateTextWidth($y)+8;
                            if($width>=$gwidth){
                                $gwidth = $width;
                            }
                            uiLaterAndWait(function () use ($oldWidth, $UXLabelEx){
                                $UXLabelEx->width = $oldWidth;
                                $this->textArea->x = $oldWidth;
                            });
                            $posx += $oldWidth;
                        }
                        $x++;
                    }
                    
                    $y++;
                }
                uiLaterAndWait(function () use ($oldWidth){
                    $this->textArea->x = $oldWidth;
                    $this->code->data('offset', $oldWidth);
                });
                if($gwidth!=$oldWidth){
                    $this->getContextForm()->updateCode = true;
                    $this->code->data('update', true);
                }elseif($this->code->data('update')){
                    uiLaterAndWait(function (){
                        $this->getContextForm()->updateWH($this->textArea->text);
                        $this->info->text = "Loaded!";
                        $this->getContextForm()->tab->text = $this->getContextForm()->text;
                    });
                }
            }else{
                sleep(1);
            }
        }
    }

}
