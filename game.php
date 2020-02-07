<script src="js/game.js?v=<?=time();?>"></script>
<script>
    $(document).ready(function(){
        load_game(<?php echo $_POST['lesson_number'];?>,<?php echo $_POST['example_number'];?>);
    }); 
</script>
    
<div class="gameArea">
    <div class="leftDiv">
        <div class="drawing">
            <div class="gameInfo">
                <div class="time"><span class="minute">00</span>:<span class="second">00</span></div>
                <div class="gameRating">
                    <div class="loadingBar"></div>
                </div>
            </div>
            <table class="drawingTable">
            </table>
        </div>
    </div>
    <div class="rightDiv">
        <div class="components">
        </div>
        <div class="navigation">
            <button class="navButton help" onclick="help()" title="Segítségkérés"><img src="img/help.svg"></button>
            <button class="navButton learn" onclick="learn()" title="Elmélet átismétlése"><img src="img/learn.svg"></button>
            <button class="navButton run" onclick="run()" title="Futtatás"><img src="img/run.svg"></button>
        </div>
    </div>
</div>
            