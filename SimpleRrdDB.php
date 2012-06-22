<?php
/**
 * Created by JetBrains PhpStorm.
 * @author pomaxa none <pomaxa@gmail.com>
 */
class SimpleRrdDB
{

    /**
     * для получения текущего значения отсчета предыдущее значение счетчика
     * вычитается из текущего и делится на интервал между отсчетами
     * (например, счетчик переданных байт для измерения скорости).
     * Переполнение счетчика обрабатывается только для типа COUNTER.
     * Счетчики могут хранить только целые 32-х или 64-х битные числа
     */
    const COUNTER = 'COUNTER';
    /**
     * получаемое значение просто кладется в rrdb
     * (например, для счетчика загрузки CPU или температуры, когда нужна не разность, а само значение)
     */
    const GAUGE = 'GAUGE';
    /**
     * COUNTER, который может уменьшаться (защиты от переполнения нет)
     */
    const DERIVE = 'DERIVE';
    /**
     * получаемое значение делится на интервал времени между отсчетами,
     * полезно для обнуляющихся при чтении источников данных
     */
    const ABSOLUTE = 'ABSOLUTE';
    /**
     * на разбирался. Если кто разбирался - буду признателен за комментарий
     */
    const COMPUTE = 'COMPUTE';

    /**
     * @var int step in seconds between values;
     */
    protected $step = 300;

    /**
     * @var string
     */
    protected $start = 'now';

    protected $dbName= 'test.rrd';

    /**
     * @var array DataSource
     */
    protected $dsa = array();
    protected $rraa = array();
    /**
    DS:имя_источника:тип_источника:интервал_определенности:min:max \
    RRA:функция_конс:достоверность:отсчетов_на_ячейку:число_ячеек
     */

    /**
     * @param int $step
     * @param int $start
     */
    public function __construct($dbName, $step = 300, $start = 0)
    {
        $this->setStart($start);
        $this->setStep($step);
        $this->setDbName($dbName);
    }


    /**
     * @param $name
     * @param $type
     * @param int $interval seconds between values
     * @param string $min
     * @param string $max
     */
    public function addDataSource($name, $type, $interval = 600, $min = 'U', $max = 'U')
    {
        $this->dsa[] = "DS:$name:$type:$interval:$min:$max";
    }

    public function addRoundRobinArchive($type, $reliability, $reportsOnCell, $cellCount)
    {
        $this->rraa[] = "RRA:$type:$reliability:$reportsOnCell:$cellCount";
    }

    public function create($overwriteFile = false)
    {

        if(file_exists($this->dbName) && $overwriteFile == false) {
            return false;
        }


        $opts = array(
            "--step", $this->getStep(),
            "--start", $this->getStart(),
        );
        //add data-sources
        $opts = $opts + $this->dsa;
        //add round-robin archives
        $opts = $opts + $this->rraa;

        //try to create db file
        $ret = rrd_create($this->dbName, $opts);

        if( $ret == 0 )
        {
            $err = rrd_error();
            throw new \Exception("Create error: $err\n");
        }
        return true;
    }

    /**
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    public function setDbName($dbName)
    {
        if(substr($dbName,-4,4) == '.rrd')
        {
            $this->dbName = $dbName;
        }
        else
        {
            $this->dbName = $dbName . ".rrd";
        }
    }

    public function getDbName()
    {
        return $this->dbName;
    }

}
