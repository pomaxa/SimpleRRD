<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pomaxa
 * Date: 6/22/12
 * Time: 3:52 PM
 * To change this template use File | Settings | File Templates.
 */


namespace SimpleRRD;


require './../SimpleRrdDB.php';

$rrd = new SimpleRrdDB('test');
$rrd->addDataSource('gold', DSType::GAUGE, 60);
$rrd->addDataSource('diamonds', DSType::GAUGE, 60);

$rrd->addRoundRobinArchive(RRAType::AVERAGE, 0.5, 1, 600);
$rrd->addRoundRobinArchive(RRAType::MAX, 0.5, 1, 600);
