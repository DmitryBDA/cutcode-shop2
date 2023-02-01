<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Sentry\Stacktrace;

trait HasSlug
{
    protected static function bootHasSlug():void
    {
        static::creating(function (Model $item) {
            $item->slug = $item->slug
                ?? static::checkAndGetUniqSlug($item->{self::slugFrom()});
        });
    }

    public static function checkAndGetUniqSlug($title): string
    {
        $slug = str($title)->slug();

        if (static::whereSlug($slug)->exists()) {

            $max = static::where(self::slugFrom(), $title)->latest('id')->value('slug');

            if (isset($max[-1]) && is_numeric($max[-1]) && preg_match('/(-\d+)$/', $max)) {

                return preg_replace_callback('/(\d+)$/', function ($matches) {
                    return $matches[1] + 1;
                }, $max);
            }
            return $slug . '-2';
        }
        return $slug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
