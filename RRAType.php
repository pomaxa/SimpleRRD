<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pomaxa
 * Date: 6/22/12
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */
namespace SimpleRRD;

class RRAType
{
    //высчитывается среднее арифметическое всех отсчетов
    const AVERAGE = 'AVERAGE';
    //максимальное значение отсчетов соответственно
    const MAX = 'MAX';
    //минимальное значение отсчетов соответственно
    const MIN = 'MIN';
    //последний полученный отсчет
    const LAST = 'LAST';
}
