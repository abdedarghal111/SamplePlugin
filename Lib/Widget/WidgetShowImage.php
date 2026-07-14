<?php
namespace FacturaScripts\Plugins\SamplePlugin\Lib\Widget;

use FacturaScripts\Core\Lib\MyFilesToken;
use FacturaScripts\Core\Lib\Widget\BaseWidget;
use FacturaScripts\Core\Tools;
use FacturaScripts\Dinamic\Model\AttachedFile;

class WidgetShowImage extends BaseWidget
{

    static String $html = <<<'HTML'
        <div id="%%ID%%" href="%%VALUE%%"  style="width: 100px; display: flex; align-items:center; justify-content: center;">
            <img src="%%VALUE%%" style="
                max-width: 100%;
                max-height: 100%;
                border-radius: 5px;
            "/>
        </div>

        <!-- pestaña pop up -->
        <div class="d-none" id="%%ID%%prev">
            <div class="modal-content container px-0">
                <div class="modal-header flex" style="align-items:center;">
                    <h4 class="modal-title fs-5 text-primary">%%TRAD_PREVIS%%</h4>
                    <div id="%%ID%%prev_cerrar" class="btn"><i class="fa-solid fa-xmark h4 mb-0 text-primary"></i></div>
                </div>
                <div class="modal-body d-flex flex-column align-items-center p-2">
                    <img src="%%VALUE%%">  
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" download="archivo" href="%%VALUE%%" target="_blank">%%TRAD_DESCARGAR%%</a>
                </div>
            </div>
        </div>

        <style>
            #%%ID%%prev {
                position: fixed;
                top:0px;
                left:0px;
                width: 100%;
                height:100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color:rgba(0, 0, 0, 0.58);
                z-index: 10;
            }

            #%%ID%%prev .modal-content {
                display: flex;
                flex-direction: column;
                width: fit-content;
                max-width: 70%;
                max-height: 80%;
            }

            

            #%%ID%%prev .modal-body img {
                max-width: 100%;
                max-height: 50vh;
                height: auto;
                border-radius: 15px;
            }

        </style>

        <script>
            {// encapsulation
                let opened = false
                let uperPrev = document.getElementById("%%ID%%prev")
                let openOrClose = ev => {
                    if(!opened){
                        uperPrev.classList.add("d-flex")
                        uperPrev.classList.remove("d-none")
                    }else{
                        uperPrev.classList.remove("d-flex")
                        uperPrev.classList.add("d-none")
                    }
                    opened = !opened
                }

                document.getElementById("%%ID%%").onclick = openOrClose
                uperPrev.onclick = openOrClose
                uperPrev.querySelector("div").onclick = event => {
                    event.stopPropagation()
                }
                document.getElementById("%%ID%%prev_cerrar").onclick = openOrClose
            }
        </script>
    HTML;

    public function tableCell($model, $display = 'left')
    {
        $this->setValue($model);

        $outHtml = "---";
        
        if($this->value){
            $file = new AttachedFile();
            $file->loadFromCode($this->value);
            $url = $file->url("download");

            $id = "id".$this->getUniqueId();
            
            $outHtml = self::$html;
            foreach ([
                '%%ID%%' => $id,
                '%%VALUE%%' => $url,
                '%%TRAD_PREVIS%%' => Tools::lang()->trans('image_preview'),
                '%%TRAD_DESCARGAR%%' => Tools::lang()->trans('image_download')
            ] as $find => $replace) {
                $outHtml = str_replace($find, $replace, $outHtml);
            }
        }


        $class = $this->combineClasses($this->tableCellClass('text-' . $display), $this->class);
        //return '<td class="' . $class . '">' . $outHtml . '</td>';
        return '<td class="' . $class . " cancelClickable " . '">' . $outHtml . '</td>';
    }
}