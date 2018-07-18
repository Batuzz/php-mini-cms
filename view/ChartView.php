<?php
require_once('PageView.php');

/**
 * @author Bartosz Studnik
 */
class ChartView extends PageView {
    
    
    public function __construct() {
        
        parent::__construct();
        $this->content = '';
        $this->form = '';
        $specialContent = '<div style="width: 50%; margin-left: auto; margin-right: auto;">
                            <canvas id="canvas" height="450" width="600"></canvas>
                        </div>
                        
                <script src="media/js/Chart.js"></script>
               ';
        $this->setSpecialContent($specialContent);
    }
    
    
    public function content($content = null) {
        ?>
            <script>
                var barChartData = {
                        labels : <?=$this->prepareLabels($content);?>,
                        datasets : [
                                {
                                        fillColor : "rgba(220,220,220,0.5)",
                                        strokeColor : "rgba(220,220,220,0.8)",
                                        highlightFill: "rgba(220,220,220,0.75)",
                                        highlightStroke: "rgba(220,220,220,1)",
                                        data : <?=$this->prepareA($content);?>
                                },
                                {
                                        fillColor : "rgba(151,187,205,0.5)",
                                        strokeColor : "rgba(151,187,205,0.8)",
                                        highlightFill : "rgba(151,187,205,0.75)",
                                        highlightStroke : "rgba(151,187,205,1)",
                                        data : <?=$this->prepareB($content);?>
                                }
                        ]

                }
                window.onload = function(){
                        var ctx = document.getElementById("canvas").getContext("2d");
                        window.myBar = new Chart(ctx).Bar(barChartData, {
                                responsive : true
                        });
                }

            </script>
                <?php
    }
    
    public function additionalContent($content = null, $header = null) {
        ?>
                <header>
                    <h1><?=$header;?></h1>
                </header>
                <article>
                    <p><?=$content;?></p>
                </article>
        <?php
    }
    
    private function prepareLabels($content) {
        $toReturn = "[";
        foreach($content as $key=>$c) {
            $toReturn .= '"'.QUESTION.' '.$key.'",';
        }
        $toReturn .= "]";
        return $toReturn;
    }
    
    private function prepareA($content) {

    
        $toReturn = "[";
        foreach($content as $key=>$c) {
            if(isset($c[1]))
                $toReturn .= '"'.$c[1].'",';
            else {
                $toReturn .= '"0",';
            }
        }
        $toReturn .= "]";
        return $toReturn;
    }
    
    private function prepareB($content) {

    
        $toReturn = "[";
        foreach($content as $key=>$c) {
           if(isset($c[2]))
                $toReturn .= '"'.$c[2].'",';
            else {
                $toReturn .= '"0",';
            }
        }
        $toReturn .= "]";
        return $toReturn;
    }
}
