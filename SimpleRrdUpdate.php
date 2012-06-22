<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pomaxa
 * Date: 6/22/12
 * Time: 4:09 PM
 * To change this template use File | Settings | File Templates.
 */
class SimpleRrdUpdate
{

    /**
     * @var srting
     */
    private $rrdFile;

    /**
     * @param string $rrdFile
     */
    function __construct($rrdFile)
    {
        $this->setRrdFile($rrdFile);
    }

    /**
     * Set rrd file
     * @param string $rrdFile
     */
    public function setRrdFile($rrdFile)
    {
        if(substr($rrdFile,-4,4) == '.rrd')
        {
            $this->rrdFile = $rrdFile;
        }
        else
        {
            $this->rrdFile = $rrdFile . ".rrd";
        }
        $this->rrdFile = $rrdFile;
    }

    /**
     * Get rrd file
     * @return string
     */
    public function getRrdFile()
    {
        return $this->rrdFile;
    }

    public function update($data, $time = null) {
        if(empty($time)) {
            $time = time();
        }
        $updater = new RRDUpdater($this->rrdFile);

        return $updater->update( $data, $time );
    }


}
