<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pomaxa
 * Date: 6/22/12
 * Time: 3:54 PM
 * To change this template use File | Settings | File Templates.
 */
namespace SimpleRRD;

class DSType
{
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
}
