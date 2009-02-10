<?php
setlocale(LC_ALL,'de_DE');

include("header.inc.php");

$back_year = 0;
$back_monat = 0;

/**
 * View Single Logfile by appeding date to URL, like: "?date=2009-02-09"
 */
$date = $_GET['date'];
if (isset($date) && preg_match("/^\d\d\d\d-\d\d-\d\d$/", $date))
{
    $jahr = substr($date, 0, 4);
    $monat = substr($date, 5, 2);
    $day = substr($date,8,2);
?>
    <p>
        <a href="./">Index</a>
    </p>

    <h2>IRC Log for <?php echo strftime("%A, %d. %B %Y", mktime(0, 0, 0, $monat, $day, $jahr)); ?></h2>

    <p>
        Timestamps are in GMT/BST.
    </p>
    <p>
    <?php
            readfile($date . ".log");
    ?>
    </p>
<?php
}
/**
 * Display Overview of Logfiles available
 */
else
{
    # Alle Logfiles einlesen
    $dir = opendir(".");
    while (false !== ($file = readdir($dir)))
    {
        if (strpos($file, ".log") == 10)
        {
            $filearray[] = $file;
        }
    }
    closedir($dir);

    # Array aufbereiten
    foreach($filearray as $file)
    {
        $file   = substr($file, 0, 10);

    	$jahr   = substr($file, 0, 4);
    	$month  = substr($file, 5, 2);
        $day    = substr($file, 8, 2);

    	$years_array["$jahr"]["$month"]["$day"] = $file;
    }
    asort($years_array, SORT_STRING);

    /** OUTPUT **/
    ?>
    <ul>Year(s):
    <?php
    # Display Links for all Years
    foreach ($years_array as $year => $months)
    {
        echo "<a href='#$year'>$year</a> ";
    }

    foreach ($years_array as $year => $months)
    {
        # Year Name + Anchor
    	echo "<h3><a name='$year'>$year</a></h3>";

        echo "Month(s): ";
        # Display Links for all Months
        foreach($months as $month => $days)
        {
            $monthname = strftime("%B", mktime(0, 0, 0, $month, '01', $year));
            echo "<a href='#$year-$month'>$monthname</a> ";
        }

        echo "<br />";

        foreach ($months as $month => $days)
        {
            $monthname = strftime("%B", mktime(0, 0, 0, $month, '01', $year));
            echo "<h3><a name='$year-$month'>$monthname</a></h3>";
            echo "<blockquote>";
            foreach($days as $day => $filename)
            {


                echo "<li>";
                ?>
                <a href="<?php echo($_SERVER['PHP_SELF'] . "?date=" . $filename); ?>">
                <?php
                echo strftime("%A, %d. %B %Y", mktime(0, 0, 0, $month, $day, $year));
                echo "</a>";
                echo "</li>";
            }
            echo "</blockquote>";
        }
    }

    ?></ul><?php
}
include("footer.inc.php");
?>