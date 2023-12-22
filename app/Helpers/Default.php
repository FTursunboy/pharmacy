<?php

use App\Models\Language;
use App\Models\SectionActivity;
use App\Models\SystemSetting;
use GreenSMS\GreenSMS;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

function requestOrder()
{
    $order = request()->get('order', '-id');
    if ($order[0] == '-') {
        $result = [
            'key' => substr($order, 1),
            'value' => 'desc',
        ];
    } else {
        $result = [
            'key' => $order,
            'value' => 'asc',
        ];
    }
    return $result;
}

function filterPhone($phone)
{
    return str_replace(['(', ')', ' ', '-'], '', $phone);
}

function uploadFile($file, $path, $old = null): ?string
{
    $result = null;
    deleteFile($old);
    if (is_file($file)) {
        $extension = $file->extension();
        $model = str_replace('.', '', microtime(true)) . '.' . $extension;
        $file->storeAs("public/$path", $model);
        $result = "/storage/$path/" . $model;
    }
    return $result;
}

function deleteFile($path): void
{
    if ($path != null && file_exists(public_path() . $path)) {
        unlink(public_path() . $path);
    }
}

function nudePhone($phone)
{
    if (strlen($phone) > 0) {
        $phone = str_replace(['(', ')', ' ', '-', '+'], '', $phone);
    }

    return $phone;
}

function buildPhone($phone): string
{
    $phone = nudePhone($phone);
    return '+7 ' . '(' . substr($phone, 0, 3) . ') '
        . substr($phone, 3, 3) . '-'
        . substr($phone, 6, 2) . '-'
        . substr($phone, 8, 2);
}

function getKey()
{
    $key = explode('.', request()->route()->getName());
    array_pop($key);
    $key = implode('.', $key);
    return $key;
}

function getRequest($request = null)
{
    return $request ?? request();
}

function defaultLocale()
{
    return Language::where('default', true)->first();
}

function allLanguage()
{
    return Language::all();
}

function defaultLocaleCode()
{
    return optional(defaultLocale())->url;
}

function paginate($items, $perPage = 10, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Collection ? $items : Collection::make($items);

    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function paginatedResponse($collection): array
{
    if (isset($collection->resource)) {
        $resource = $collection->resource;
    } else {
        $resource = $collection;
        $collection = $collection['data'] ?? $collection?->items();
    }
    $pagination = [
        'total' => $resource->total(),
        'count' => $resource->count(),
        'per_page' => intval($resource->perPage()),
        'current_page' => $resource->currentPage(),
        'total_pages' => $resource->lastPage(),
    ];
    return ['data' => $collection, 'pagination' => $pagination];
}

function paginatedResponseChat($collection): array
{
    if (isset($collection->resource)) {
        $resource = $collection->resource;
    } else {
        $resource = $collection;
        $collection = $collection['data'];
    }
//    $resource = $collection->resource;
    $pagination = [
        'total' => $resource->total() + 1,
        'count' => $resource->count(),
        'per_page' => intval($resource->perPage()),
        'current_page' => $resource->currentPage(),
        'total_pages' => $resource->lastPage(),
    ];
    return ['data' => $collection, 'pagination' => $pagination];
}

/**
 * @throws Exception
 */
function sendVoice($phone, $text)
{
    $client = new GreenSMS(['user' => config('sms.user'), 'pass' => config('sms.pass')]);
    return $client->voice->send(['to' => nudePhone($phone), 'txt' => $text]);
}

function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
{
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
    }

    $links = array();

    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
        return '<' . array_push($links, $match[1]) . '>';
    }, $value);

    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':
                $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    if ($match[1]) $protocol = $match[1];
                    $link = $match[2] ?: $match[3];
                    return '<' . array_push($links, "<u><a target='blank' onclick='return confirm(`Вы уверены?`)' $attr href=\"$protocol://$link\">$link</a></u>") . '>';
                }, $value);
                break;
            case 'mail':
                $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, "<u><a target='blank' onclick='return confirm(`Вы уверены?`)' $attr href=\"mailto:{$match[1]}\">{$match[1]}</a></u>") . '>';
                }, $value);
                break;
            case 'twitter':
                $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, "<u><a target='blank' onclick='return confirm(`Вы уверены?`)' $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . "\">{$match[0]}</a></u>") . '>';
                }, $value);
                break;
            default:
                $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    return '<' . array_push($links, "<u><a target='blank' onclick='return confirm(`Вы уверены?`)' $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a></u>") . '>';
                }, $value);
                break;
        }
    }

    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
        return $links[$match[1] - 1];
    }, $value);
}

function systemSetting()
{
    return SystemSetting::where('id', 1)->firstOrCreate();
}

function incrementSectionActivity($column = 'posts')
{
    $model = SectionActivity::firstOrCreate(['id' => 1]);
    $model->$column = $model->$column + 1;
    $model->save();
}
