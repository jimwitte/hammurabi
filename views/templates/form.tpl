<form action="index.php" method="post" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-sm-4" for="acresBought">How many acres do you with to buy?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresBought" id="acresBought">
                </div>
                <p class="help-block container">help instructions.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="acresSold">How many acres do you with to sell?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresSold" id="acresSold">
                </div>
                <p class="help-block container">help instructions.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="bushelsFed">How many bushels do you wish to feed your people?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="grainFed" id="grainFed">
                </div>
                <p class="help-block container">20 bushels will feed 1 person. You need <strong>{$game->population * 20}</strong> bushels to feed everyone this year.</p>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4" for="acresPlanted">How many acres do you wish to plant with seed?</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="acresPlanted" id="acresPlanted">
                </div>
                <p class="help-block container">2 bushels are required to plant 1 acre. A person can work at most 10 acres. 
                You can plant at most <strong>{min($game->population * 10, $game->grainStored/2)}</strong> acres.
                </p>
            </div>            
            <button type="submit" name="submit" id="next" value="next" class="btn btn-success" style="margin-bottom: 2em;">So let it be written, so let it be done!</button>
            <button type="submit" name="submit" id="reset" value="reset" class="btn" style="margin-bottom: 2em;">Reset</button>

</form>