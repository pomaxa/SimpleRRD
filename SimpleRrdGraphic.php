<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pomaxa
 * Date: 6/22/12
 * Time: 1:33 PM
 * To change this template use File | Settings | File Templates.
 */
class SimpleRrdGraphic
{

    /**
     * точка начала отсчета графика
     * @var
     */
    private $startTime;
    /**
     * конечная временная точка графика
     * @var
     */
    private $endTime;
    /**
     * определяет шаг (в секундах). Обратите внимание на то, что если шаг менее 1 пикселя на графике, часть данных не будет отображаться
     * @var
     */
    private $step;



    /**
     * Заголовки
     */

    /**
     * заголовок над графика (кириллицу не понимает)
     * @var
     */
    private $title;
    /**
     * заголовок слева от графика
     * @var
     */
    private $verticalLabel;


    /**
     * Размеры
     */

    /**
     * ширина и высота графика (области данных графика) соответственно, указывается в пикселях,
     * по умолчанию rrdtool делает график размером 400х100 пикселей
     * @var
     */
    private $width;
    /**
     * ширина и высота графика (области данных графика) соответственно, указывается в пикселях,
     * по умолчанию rrdtool делает график размером 400х100 пикселей
     * @var
     */
    private $height;

    /**
     * при наличии этой опции, и значении --height<32, создается .иконка. графика без до
     * @var
     */
    private $onlyGraph;


    /**
     * Лимиты
     * По умолчанию график будет автоматически масштабироваться таким образом,
     * чтобы Y-значения всего выводимого диапазона помещались на графике,
     * однако это можно изменить вручную, указав необходимые пределы параметрами
     * --lower-limit и --upper-limit.
     * Тем не менее автоматическое масштабирование будет обеспечивать отображение всего графика,
     * даже если там окажутся значения вне указанного диапазона до того,
     * как Вы установите опцию rigid.
     */

    /**
     * нижний лимит значений. Все, что меньше, будет увеличиваться до этого значения
     * @var
     */
    private $lowerLimit;
    /**
     * верхний лимит значение. Все, что превышает, будет усекаться до этого значения
     * @var
     */
    private $upperLimit;

    /**
     * В некоторых случаях обычный алгоритм определения масштаба оси Y не назовешь удачным.
     * Обычно масштаб выбирается из заранее предопределенных диапазонов, что будет катастрофически
     * не удачной идеей, если надо нарисовать график функции типа 260+0.001*sin(x).
     * Наличие данной опции включает альтернативный расчет минимума и максимума, отображаемого на оси Y,
     * исходя из существующих минимальных и максимальных значений, отображаемых на графике
     * @var
     */
    private $altAutoscale;


    /**
     * опция аналогична --alt-autoscale, но в отличии от нее затрагивает только верхнюю границу графика,
     * а нижнюю границу устанавливает в .0.. Эта опция полезна в тех случаях,
     * когда выводится скорость на WLAN порту, которая может оказаться больше номинальной за счет компрессии
     * @var
     */
    private $altAutoscaleMax;


    /**
     * Система растягивает шкалы таким образом, чтобы сетка ложилась на целочисленные значения шкал.
     * В некоторых случаях это может привести к чрезмерному вытягиванию, для чего его можно запретить этой опцией
     * @var
     */
    private $noGridFit;


    /**
     * Сетка
     */

    /**
     * Ось X достаточно сложна для настройки, поэтому не стоит что-то менять без крайней необходимости.
     * Если необходимо убрать сетку и подписи, относящиеся к оси X, просто укажите опцию --x-grid none.
     * Формат опции следующий:
     *    --x-grid GTM:GST:MTM:MST:LTM:LST:LPR:LFM
     *
     * Сетка определяется указанием точных временных величин параметрами ?TM.
     * Там можно указать одно из следующих значений: SECOND, MINUTE, HOUR, DAY, WEEK, MONTH или YEAR.
     * Далее определяется какое именно количество этих величин необходимо помещать
     * между линиями и надписями (параметры ?ST). Такие пары (?TM:?ST) необходимо определить для
     * базовой сетки (G??), основной сетки (M??) и подписей (L??).
     * Для подписей также не обходимо указать положение (параметр LPR) и формат строки (параметр LFM).
     * Положение, определяемое параметром LPR, может быть справа от линии (значение 0),
     * а при указании там количества секунд, подпись будет центрироваться относительно указанного сдвига.
     *
     * @var
     */
    private $xGrid;

    /**
     * определяет частоту горизонтальной сети и подписи у оси Y.
     * Если необходимо убрать горизонтальную сетку необходимо указать --y-grid none.
     * Для определения собственного вида горизонтальной сетки и подписей у оси Y используется следующий формат:
     * --y-grid ШАГ_СЕТКИ:ПРИЗНАК_МЕТКИ, где ШАГ_СЕТКИ . это, соответственно шаг сетки,
     * а ПРИЗНАК_МЕТКИ определяет частоту подписей
     *
     * @var
     */
    private $yGrid;

    private $altYGrid;
    /**
     * делает логарифмическое масштабирование по оси Y
     * @var
     */
    private $logarithmic;

    /**
     * @var
     */
    private $unitsExponent;
    private $unitsLength;

    /**
     * разная всячина
     */

    /**
     * обновлять график только при устаревании целевого файла или его отсутствии (смотрится mtime)
     * @var
     */
    private $lazy;

    private $imginfo;


    /**
     * задание цвета различных элементов графика (опцию можно использовать несколько раз для указания цвета различных элементов). Используется в виде --color TAG:#rgbcolor, где TAG - один из элементов графика:
    BACK - фон
    CANVAS - окаймление
    SHADEA - границы справа и сверху
    SHADEB - границы слева и снизу
    GRID - сетка
    MGRID - основная сетка
    FONT - шрифт
    FRAME - границы непосредственно графика
    ARROW - стрелки
     */
    private $color;


    /**
     * масштабирует график на заданную величину. Параметр должен быть больше 0
     * @var
     */
    private $zoom;

    /**
     * позволяет переопределить шрифты, которые будут использоваться для различных текстовых элементов рисунков RRD. Для определения параметров шрифта используется следующий формат: --font TAG:РАЗМЕР:[ФАЙЛ_ШРИФТА]. Если указать размер шрифта равным нулю, то его размер не будет меняться. Это удобно в том случае, если необходимо изменить шрифт не меняя его размер. RRDTool поставляется с предустановленным шрифтом. Его можно изменить через переменную окружения RRD_DEFAULT_FONT. Если Вы используете формат вывода PNG, то можно использовать только TrueType-шрифты. Параметр TAG определяет элемент, для которого устанавливается шрифт. Может принимать следующие значения
    DEFAULT - определяет значения по умолчанию для всех элементов
    TITLE - определяет параметры заголовков
    AXIS - параметры подписей осей графика
    UNIT - параметры единиц изменения
    LEGEND - параметры легенды
    Например, для того, чтобы установить шрифт Times размера 13 для заголовка, необходимо указать следующее:
    --font TITLE:13:/usr/lib/fonts/times.ttf
     * @var
     */

    private $font;

    /**
     * позволяет определить силу сглаживания текста. Может принимать следующие значения
    normal - установлено по умолчанию
    light - хз
    mono - отключаени сглаживание
    ч
     * @var
     */
    private $fontRenderMode;


    /**
     * определяет максимальный размер шрифта, который будет выводиться без сглаживания.
     * По умолчанию одция не установлена вовсе
     * @var
     */
    private $fontSmoothingThreshold;

    private $slopeMode;

    /**
     * тип изображения (GIF, PNG или GD)
     * @var string
     */
    private $imgformat = 'png';


    /**
     * обеспечивает более высокую скорость загрузки рисунков в браузере
     * @var
     */
    private $interlaced;

    /**
     * запретить генерацию легенды, генерировать только график
     * @var
     */
    private $noLegend;


    /**
     * гарантирует вывод горизонтальной и вертикальной легенды, даже если она не помещается на рисунке целиком
     */
    private $forceRulesLegend;

    /**
     * позволяет установить размер табуляции. По умолчанию установлено значение 40 пикселей
     * @var
     */
    private $tabwidth;
    private $base;
    /**
     * не растягивать график, если встречаются значения, выходящие за верхний или нижний лимиты
     * @var
     */
    private $rigid;


    /**
     * @var
     */
    public $rrdFile;

    public $ds;

    public function draw()
    {

    }

}