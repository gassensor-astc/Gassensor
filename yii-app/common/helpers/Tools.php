<?php
/**
 *
 * @since 2019-04-26 15:35
 */

namespace common\helpers;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use SimpleXMLElement;
use yii\base\{Model, DynamicModel};
use yii\data\ArrayDataProvider;
use yii\helpers\{ArrayHelper, Json};


class Tools
{
    public static $months = [
        'январь',
        'февраль',
        'март',
        'апрель',
        'май',
        'июнь',
        'июль',
        'август',
        'сентябрь',
        'октябрь',
        'ноябрь',
        'декабрь',
    ];

    /**
     * @param $arr
     * @return string
     */
    public static function getWidgetColumnsFromArray($arr)
    {
        $model = new DynamicModel($arr);
        return self::getWidgetColumns($model);
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function getWidgetColumns(Model $model)
    {
        //dump($model->attributes);

        $cols1 = [];
        $cols2 = [];
        $cols3 = [];
        $cols4 = [];
        foreach ($model->attributes as $attrName => $attrVal) {
            $cols1[] = "  '$attrName'";
            $cols2[] = "[\n  'label' => '$attrName',\n  'value' => '$attrName',\n]";
            $cols3[] = "[\n  'label' => '$attrName',\n  "
                . "'format' => 'raw',\n  "
                . "'value' => function(\$value) {\n    return \$value;\n  },\n]";
            $cols4[] = "[\n  'label' => '$attrName',\n  "
                . "'format' => 'raw',\n  "
                . "'value' => function(\$model) {\n    return \$model->$attrName;\n  },\n]";
        }

        $cols5 = [];
        foreach ($model->relatedRecords as $relName => $relModel) {
            if (!$relModel) {
                continue;
            }
            foreach ($relModel->attributes as $name => $val) {
                $cols5[] = "'$relName.$name'";
            }

        }

        $str = "<div class='row'>";

        $str .= "<div class='col col-md-3'><pre>\n";
        $str .= "[\n" . join(",\n", $cols1) . ",\n]";
        $str .= "\n[\n" . join(",\n", $cols5) . ",\n]";
        $str .= "\n</pre></div>";


        $str .= "<div class='col col-md-3'><pre>\n";
        $str .= join(",\n", $cols2) . ",\n";
        $str .= "\n</pre></div>";

        $str .= "<div class='col col-md-3'><pre>\n";
        $str .= join(",\n", $cols3) . ",\n";
        $str .= "\n</pre></div>";

        $str .= "<div class='col col-md-3'><pre>\n";
        $str .= join(",\n", $cols4) . ",\n";
        $str .= "\n</pre></div>";

        $str .= "</div>";

        return $str;
    }

    /**
     * @param SimpleXMLElement $sxe
     * @return \yii\data\ArrayDataProvider
     */
    public static function getDataProviderFromSxe(SimpleXMLElement $sxe)
    {
        $dp = new ArrayDataProvider();
        $models = [];

        foreach ($sxe as $v) {
            $row = array_map(function ($item) {
                return (string)$item;
            }, (array)$v);
            $models[] = $row;
        }

        $dp->setModels($models);

        return $dp;
    }

    /**
     * @param $arr
     * @return mixed
     */
    public static function arrayValsToScalar(array $arr)
    {
        foreach ($arr ?? [] as &$v) {
            if (null !== $v && !is_scalar($v)) {
                $v = Json::encode($v);
            }
        }

        return $arr;
    }

    /**
     * @param $str
     * @return array|string|string[]|null
     */
    public static function filterAlphaNum($str)
    {
        return preg_replace('/[^a-z0-9]+/i', '', $str);
    }

    /**
     * @param $str
     * @return array|string|string[]|null
     */
    public static function filterNum($str)
    {
        return preg_replace('/[^0-9]+/', '', $str);
    }

    /**
     * @param $str
     * @return array|string|string[]|null
     */
    public static function filterNumAndDash($str)
    {
        return preg_replace('/[^0-9-]+/', '', $str);
    }

    /**
     * https://www.php.net/manual/en/function.array-unshift.php#106570
     * @param array $arr
     * @param $key
     * @param $val
     */
    public static function array_unshift_assoc(&$arr, $key = '', $val = null)
    {
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        return array_reverse($arr, true);
    }

    public static function jsonErrors()
    {
        return [
            JSON_ERROR_NONE => 'JSON_ERROR_NONE',
            JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH',
            JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH',
            JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR',
            JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX',
            JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8',
            JSON_ERROR_RECURSION => 'JSON_ERROR_RECURSION',
            JSON_ERROR_INF_OR_NAN => 'JSON_ERROR_INF_OR_NAN',
            JSON_ERROR_UNSUPPORTED_TYPE => 'JSON_ERROR_UNSUPPORTED_TYPE',
            JSON_ERROR_INVALID_PROPERTY_NAME => 'JSON_ERROR_INVALID_PROPERTY_NAME',
            JSON_ERROR_UTF16 => 'JSON_ERROR_UTF16',
        ];
    }

    /**
     * @param $time
     * @return int|null
     */
    public static function getTimeH($time)
    {
        return $time ? (int)date('H', $time) : null;
    }

    /**
     * @param $time
     * @param $h
     * @param false $zerozero
     * @return false|int
     */
    public static function setTimeH($time, $h, $zerozero = false)
    {
        $newH = str_pad((int)$h, 2, '0', STR_PAD_LEFT);
        $format = $zerozero ? '00:00' : 'i:s';
        $newD = date("Y-m-d $newH:$format", $time);
        return strtotime($newD);
    }

    /**
     * @param int $time
     * @return number 1..7
     */
    public static function getTimeDayWeek($time)
    {
        return (int)date('N', $time);
    }

    /**
     * @param null $meta
     * @return array|mixed|null
     */
    public static function getSphinxMeta($meta = null)
    {
        $sphinx = Yii::$app->sphinx;

        if (!$meta) {
            $rows = $sphinx->createCommand("SHOW META")->queryAll();
            $meta = ArrayHelper::map($rows, 'Variable_name', 'Value');
        }

        $meta['keywords'] = [];

        foreach ($meta as $k => $v) {
            //$k = 'keyword[123]'; //test
            if (preg_match('%keyword\[(\d+)\]%', $k, $m)) {
                $meta['keywords'][(int)$m[1]] = $v;
            }
        }

        return $meta;
    }

    /**
     * @param $arr
     * @return mixed
     */
    public static function arrayFlattenJson($arr)
    {
        foreach ($arr as &$v) {
            if (!is_scalar($v)) {
                $v = Json::encode($v);
            }
        }

        return $arr;
    }

    /**
     * http://jeffreysambells.com/2012/10/25/human-readable-filesize-php
     * @param int $bytes
     * @param number $decimals
     * @return string
     */
    public static function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * @param $route
     * @param $name
     * @param $value
     * @return array
     */
    public static function addParamToRoute($route, $name, $value)
    {
        $currentParams = Yii::$app->request->get();
        $currentParams[$name] = $value;
        return array_merge($route, $currentParams);
    }

    /**
     *
     * @param string $append
     * @param string $prepend example xdebug on: 'export XDEBUG_CONFIG="remote_host=192.168.0.20 idekey=*complex*" &&'
     * @return array
     */
    public static function cliYii($append = '', $prepend = '')
    {
        $parts = [
            $prepend,
            \Yii::getAlias('@yiiapp/yii'),
            $append,
            '2>&1',
        ];

        $cmd = join(' ', $parts);

        $handler = popen($cmd, 'r');
        $output = '';
        while (!feof($handler)) {
            $output .= fgets($handler);
        }
        $output = trim($output);
        $status = pclose($handler);

        return ['output' => $output, 'status' => $status];
    }

    /**
     * @param $attrname
     * @param array $editableOptions
     * @param null $label
     * @return array
     */
    public static function getEditableGridCol($attrname, $editableOptions = [], $label = null)
    {
        $editableOptionsInit = [
            'asPopover' => false,
            'closeOnBlur' => true,
            'options' => ['style' => 'width: 80px; font-size: 1em;', 'class' => 'form-control-sm'],
            'inlineSettings' => [
                //'templateAfter' => '{buttons}',
                'templateAfter' => '',
            ],
            'formOptions' => [
                'action' => ['update-cell'],
            ],
        ];

        $editableOptions = ArrayHelper::merge($editableOptionsInit, $editableOptions);

        return [
            'class' => 'kartik\grid\EditableColumn',
            'label' => $label,
            'attribute' => $attrname,
            'editableOptions' => $editableOptions,
        ];
    }

    public static function appInfo()
    {
        $str = 'php:' . phpversion();
        $filename = \Yii::getAlias('@root/REVISION');
        if (is_file($filename)) {
            $str .= ' | build:' . date('Ymd-His', filemtime($filename));
        }

        $str .= ' | ' . date('Y-m-d H:i:s');

        return $str;
    }

    /**
     * @param $path
     * @return false|int
     */
    public static function isPict($path)
    {
        return preg_match('%\.(png|jpg|webp|gif)$%', $path);
    }

    /**
     * @param $filename
     * @return mixed|string
     */
    public static function makeFilenameUnique($filename)
    {
        if (!is_file($filename)) {
            return $filename;
        }

        $info = pathinfo($filename);

        $prefix = "{$info['dirname']}/{$info['filename']}-";
        $suffix = $info['extension'];
        $suffix = '.' . str_replace(['jpeg'], ['jpg'], $suffix);

        $i = 0;

        do {
            $filename = $prefix . (++$i) . $suffix;
        } while (is_file($filename));

        return $filename;
    }

    /**
     * @param $arg
     * @return String
     */
    public static function sqlFormatting($arg)
    {
        $sql = $arg;//todo allow query obj
        return \SqlFormatter::format($sql);
    }

    /**
     * @param $url
     * @return string|void
     */
    public static function urlToPath($url)
    {
        if (null === $url) {
            return;
        }
        $url = preg_replace('%^http(s?)://[^/]+%', '', $url);

        $url = trim($url, ' /');

        return '/' . $url;
    }

    /**
     * @param \yii\db\Query $q
     * @param $filename
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\db\Exception
     */
    public static function queryToExcel(\yii\db\Query $q, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle("A:Z")
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        $rows = $q->createCommand()->queryAll();

        if ($rows) {
            $i = 1;

            foreach ($sheet->getColumnDimensions() as $v) {
                $v->setWidth(40);
                //echo "\n $i";
                if ($i++ >= count($rows[0])) {
                    break;
                }
            }

        }

        $sheet->fromArray($rows);

        /* Here there will be some code where you create $spreadsheet */

        // redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');

        //$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        //return $writer->save('php://output');


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function checkStringType(string $value): string
    {
        if (preg_match('/^-?\d+$/', $value)) {
            return "int";
        } elseif (preg_match('/^-?\d+\.\d+$/', $value)) {
            return "float";
        } elseif (preg_match('/^-?\.\d+$/', $value)) {
            return "float";
        } else {
            return "string";
        }
    }

    /**
     * Параметр пагинации `page` из СЫРОЙ строки запроса (без учёта параметров
     * сматченного роута — см. getPathFormPage()).
     *
     * @return string|null значение как есть; null — параметра в запросе нет
     */
    public static function getRawPageParam(): ?string
    {
        $params = self::getRawQueryParams();

        if (!isset($params['page']) || $params['page'] === '') {
            return null;
        }

        return (string)$params['page'];
    }

    /**
     * Номер страницы, если запрос пришёл ПУТЕВОЙ формой пагинации
     * (`/<section>/page/<N>`). Такие адреса дублируют `/<section>?page=<N>`.
     *
     * ВАЖНО: смотрим СЫРУЮ строку запроса — Yii подмешивает параметры
     * сматченного роута в query-параметры (`/news/page/2` → `page=2` в
     * queryParams), поэтому по queryParams путевой адрес от формы с
     * параметром не отличить (и редирект уходит сам на себя — петля).
     *
     * @return int|null
     */
    public static function getPathFormPage(): ?int
    {
        if (array_key_exists('page', self::getRawQueryParams())) {
            return null;
        }

        $page = Yii::$app->request->get('page', null);

        if ($page === null || $page === '') {
            return null;
        }

        return (int)$page;
    }

    /**
     * GET-параметры текущего запроса без `page` (пустые значения отброшены).
     *
     * @return array
     */
    public static function getQueryParamsWithoutPage(): array
    {
        $params = self::getRawQueryParams();
        unset($params['page']);

        return array_filter($params, static function ($value) {
            return $value !== '' && $value !== null && $value !== [];
        });
    }

    /**
     * Куда 301-ить для разделов, где каноническая форма пагинации — с
     * параметром (`/<section>?page=<N>`, страница 1 — без параметра):
     * /news, /remains.
     *
     * @param string $basePath например '/news'
     * @return string|null адрес для редиректа; null — запрос уже канонический
     */
    public static function getQueryFormPageRedirectTarget(string $basePath): ?string
    {
        $rawPage = self::getRawPageParam();
        $pathPage = self::getPathFormPage();

        // Пришло параметром и это не первая страница — адрес уже канонический.
        if ($rawPage !== null && (int)$rawPage > 1) {
            return null;
        }

        // Ни путевой формы, ни параметра — трогать нечего.
        if ($pathPage === null && $rawPage === null) {
            return null;
        }

        $page = (int)($pathPage ?? $rawPage);
        $queryParams = self::getQueryParamsWithoutPage();

        if ($page > 1) {
            $queryParams = array_merge(['page' => $page], $queryParams);
        }

        return $basePath . ($queryParams ? '?' . http_build_query($queryParams) : '');
    }

    /**
     * Обратная политика: каноническая форма пагинации — ПУТЕВАЯ
     * (`/<section>/<N>`, так у индекса каталога), а `?page=<N>` 301-им на неё.
     * Страница 1 — без номера в пути.
     *
     * @param string $basePath например '/catalog'
     * @return string|null адрес для редиректа; null — параметра в запросе нет
     */
    public static function getPathFormPageRedirectTarget(string $basePath): ?string
    {
        if (($rawPage = self::getRawPageParam()) === null) {
            return null;
        }

        $page = (int)$rawPage;
        $queryParams = self::getQueryParamsWithoutPage();
        $target = $page > 1 ? $basePath . '/' . $page : $basePath;

        return $target . ($queryParams ? '?' . http_build_query($queryParams) : '');
    }

    /**
     * Убрать параметр пагинации из адреса разделов БЕЗ пагинации: и путевая
     * форма `/<section>/page/<N>`, и `?page=<N>` ведут на `/<section>`.
     *
     * @param string $basePath например '/applications'
     * @return string|null адрес для редиректа; null — параметра в запросе нет
     */
    public static function getPageParamStrippedRedirectTarget(string $basePath): ?string
    {
        if (self::getRawPageParam() === null && self::getPathFormPage() === null) {
            return null;
        }

        $queryParams = self::getQueryParamsWithoutPage();

        return $basePath . ($queryParams ? '?' . http_build_query($queryParams) : '');
    }

    /**
     * @return array
     */
    private static function getRawQueryParams(): array
    {
        $params = [];
        parse_str((string)Yii::$app->request->getQueryString(), $params);

        return $params;
    }
}
