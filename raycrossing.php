<?php

/*
 * @param point array[lat => number, long => number]
 * @param vertices array[point1, point2, ...]
 */
function _pointInsidePolygon( $point, $vertices ) {
        if ( count( $vertices ) <= 2 ) {
                return false;
        }

        $crossings = 0;
        for ($i=0; $i < count($vertices); $i++) {
                $j = $i + 1;
                if ($j >= count($vertices)) { $j = 0; }
                if (rayCrossesSegment($point,$vertices[$i], $vertices[$j])) {
                        $crossings++;
                }
        }

        return ($crossings % 2) == 1;
}


function rayCrossesSegment($point, $a, $b) {
        $px = $point['long'];
        $py = $point['lat'];
        $ax = $a['long'];
        $ay = $a['lat'];
        $bx = $b['long'];
        $by = $b['lat'];
        if ($ay > $by) {
            $ax = $b['long'];
            $ay = $b['lat'];
            $bx = $a['long'];
            $by = $a['lat'];
        }
        if ($px < 0) { $px += 360; }
        if ($ax < 0) { $ax += 360; }
        if ($bx < 0) { $bx += 360; }

        if ($py == $ay || $py == $by) $py += 0.00000001;
        if (($py > $by || $py < $ay) || ($px > max($ax, $bx))) return false;
        if ($px < min($ax, $bx)) return true;

        $red = ($ax != $bx) ? (($by - $ay) / ($bx - $ax)) : PHP_INT_MAX;
        $blue = ($ax != $px) ? (($py - $ay) / ($px - $ax)) : PHP_INT_MAX;
        return ($blue >= $red);
}

$rect = array(
        array("lat" => 52.464069, "long" => -109.002071),
        array("lat" => 52.464226, "long" => -109.013400),
        array("lat" => 52.471207, "long" => -109.013572),
        array("lat" => 52.471076, "long" => -109.002064)
);

$point = array (
        array("lat" => 52.467677, "long" => -109.007650),       // Inside
        array("lat" => 52.467337, "long" => -109.016834),       // Outside
        array("lat" => 52.467337, "long" => -109.006834),       // Inside
        array("lat" => 52.461716, "long" => -109.005375)        // Outside
);

for ($i=0;$i<count($point); $i++) {
        echo "Point " . ($i+1) . ": " . (_pointInsidePolygon($point[$i], $rect) ? "inside" : "outside") . "\n";
}

?>