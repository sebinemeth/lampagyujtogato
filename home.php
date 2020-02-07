<script src="js/home.js?v=<?=time();?>"></script>
<script>
$(document).ready(function(){
    load_lessons(<?php if(isset($_GET['lesson'])) echo $_GET['lesson']; ?>);
});
</script>
<div class="gameArea">
    <div class="leftDiv">
        <div class="levelSelector">
        </div>
    </div>
    <div class="rightDiv">
        <div class="statDiv">
            <h2 class="statTitle">Statisztika</h2>
            <div class="statContent">
                <div>
                    <div class="statPointLabel">Összes XP</div>
                    <div class="statPoint allXP">34</div>
                </div>
                <div>
                    <div class="statPointLabel">Valami más pont</div>
                    <div class="statPoint other">234</div>
                </div>
            </div>
        </div>
    </div>
</div>