<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hammurabi: The Game</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <div class="container">
            <h1>Hammurabi: The Game</h1>
            <p>Inspired by the BASIC game, <a href="http://en.wikipedia.org/wiki/Hamurabi">http://en.wikipedia.org/wiki/Hamurabi</a></p>

            <div class="well well-sm">
                <p class="lead">Hammurabi, I beg to report to you:</p>
                <?php
                $acresTraded = intval(filter_input(INPUT_POST, 'acresTraded', FILTER_VALIDATE_INT));
                $bushelsFed = abs(intval(filter_input(INPUT_POST, 'bushelsFed', FILTER_VALIDATE_INT)));
                $acresPlanted = abs(intval(filter_input(INPUT_POST, 'acresPlanted', FILTER_VALIDATE_INT)));
                $year = intval(filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT));
                $landValue = intval(filter_input(INPUT_POST, 'landValue', FILTER_VALIDATE_INT));
                $acresOwned = intval(filter_input(INPUT_POST, 'acresOwned', FILTER_VALIDATE_INT));
                $population = intval(filter_input(INPUT_POST, 'population', FILTER_VALIDATE_INT));
                $grainStored = intval(filter_input(INPUT_POST, 'grainStored', FILTER_VALIDATE_INT));
                $totalStarved = intval(filter_input(INPUT_POST, 'totalStarved', FILTER_VALIDATE_INT));

                //set defaults if year is 0
                if ($year === 0) {
                    $landValue = 19;
                    $acresOwned = 1000;
                    $population = 100;
                    $grainStored = 2800;
                    $totalStarved = 0;
                    $gameOver = false;
                    $year = 1;
                }


                // check inputs
                $error = false;
                if ($acresTraded < -$acresOwned) {
                    $positiveAcresTraded = abs($acresTraded);
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Oh Great One, you can't sell $positiveAcresTraded acres, you only own $acresOwned acres. Please use the back button to try again.</div>";
                    $error = true;
                }
                if ($acresPlanted > $population * 10) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Not enough people to plant $acresPlanted acres. One person can plant 10 acres at most. Please use the back button to try again.</div>";
                    $error = true;
                }
                if ($grainStored - $acresTraded * $landValue < 2 * $acresPlanted + $bushelsFed) {
                    // used more bushels than we have
                    $totalBushelsUsed = 2 * $acresPlanted + $bushelsFed + $acresTraded * $landValue;
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Impossible, Oh Great One. Your decree requires $totalBushelsUsed bushels, but you only have $grainStored bushels in storage. Please use the back button to try again.</div>";
                    $error = true;
                }

                if (!$error) {
                    if ($year < 12 and $year > 1) {
                        if ($year < 11) {
                            echo "<p>You are in <strong>year $year</strong> of your ten year reign.</p>";
                        }


                        // *** Real Estate ***
                        $grainStored = $grainStored - $acresTraded * $landValue; // spend/earn bushels by land trading
                        $acresOwned = $acresOwned + $acresTraded;
                        $landValue = rand(17, 23);
                        echo "<p>";
                        echo "You now have <strong>$acresOwned</strong> acres. ";
                        echo "Land is trading at <strong>$landValue</strong> bushels/acre. ";
                        echo "</p>";

                        // *** Agriculture ***
                        echo "<p>";
                        $yield = $acresPlanted * rand(1, 6);
                        echo "$yield bushels were harvested last year. ";
                        $grainStored = $grainStored + $yield;

                        if (rand(1, 10) < 5) {
                            // rodents!
                            $bushelsLost = intval($grainStored * rand(1, 3) / 10);
                            $grainStored = $grainStored - $bushelsLost;
                            echo "Rats ate $bushelsLost bushels. ";
                        }


                        // *** human capital ***
                        $peopleFed = intval($bushelsFed / 20);
                        $peopleHungry = $population - $peopleFed;
                        $population = $peopleFed;
                        $totalStarved = $totalStarved + $peopleHungry;

                        $grainStored = $grainStored - $bushelsFed;
                        echo "You now have <strong>$grainStored</strong> bushels in storage.";
                        echo "</p>";
                        echo "<p>";
                        if (rand(1, 20) < 4) {
                            // 15% chance of plague!
                            $population = intval($population / 2);
                            echo "A horrible plague struck! Half the people died. ";
                        }

                        if ($peopleHungry > .45 * $population) {
                            // time for a revolt
                            echo "<div class=\"alert alert-danger\" role=\"alert\">You starved $peopleHungry people in one year! Due to this extreme mismanagement you have not only been impeached and thrown out of office but you have also been declared national fink! </div>";
                            $gameOver = true;
                        }

                        if ($peopleHungry === 0) {
                            // immigration
                            $immigration = intval((20 * $acresOwned + $grainStored) / (100 * $population) + 1);
                            $population = $population + $immigration;
                        } else {
                            $immigration = 0;
                        }

                        echo "$peopleHungry people starved. $immigration people moved to your kingdom. Population is now <strong>$population</strong>. ";
                        echo "</p>";

                        if ($year > 10) {
                            include 'end.inc';
                            $gameOver = true;
                        }
                    } else {
                        // game start
                        echo "<p>Try your hand at governing ancient Sumaria for a ten-year term of office.</p>";
                        echo "<p>You are in the first year of your ten-year reign. You have <strong>$acresOwned</strong> acres. Population is <strong>$population</strong> people. You have <strong>$grainStored</strong> bushels in storage. Land is trading at <strong>$landValue</strong> bushels/acre. </p>";
                    }

                    ++$year; // advance year
                }
                ?>
            </div>
            <?php
            if (!$gameOver) {
                include 'form.inc';
            }
            ?>
            <div id="footer" style="margin-top: 25px;margin-bottom: 25px;">
                <p><a href="index.php" class="btn btn-xs btn-info">Start a new game</a><br />
                    <small>by <a href="http://www.jimwitte.com">Jim Witte</a></small>
                </p>
            </div>
        </div>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-1674526-11', 'auto');
            ga('send', 'pageview');

        </script>
    </body>
</html>