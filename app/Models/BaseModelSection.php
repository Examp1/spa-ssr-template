<?php


namespace App\Models;

use App\Service\Sitemap;
use Illuminate\Database\Eloquent\Model;

class BaseModelSection extends Model
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_ACTIVE => [
                'title'    => 'Inactive',
                'bg_color' => '#da542e',
                'color'    => '#fdfdfd'
            ],
            self::STATUS_ACTIVE     => [
                'title'    => 'Active',
                'bg_color' => '#28b779',
                'color'    => 'white'
            ]
        ];
    }

    /**
     * @return string
     */
    public function showStatus(): string
    {
        return view('admin.pieces.status', self::getStatuses()[$this->status]);
    }

    /**
     * @return string
     */
    public function showMetaInfo(): string
    {
        $status = ($this->mTitle == '' && $this->mDescription == '') ? '0' : '1';

        if ($status !== '0') $status = $this->mCreatedAs;

        $mAutoGen = $this->mAutoGen;

        return view('admin.pieces.meta_info', [
            'status'   => $status,
            'mAutoGen' => $mAutoGen
        ]);
    }

    public function showAllLanguagesNotEmpty()
    {
        return view('admin.pieces.active-languages', [
            'langs' => $this->getAllLanguagesNotEmpty()
        ]);
    }

    /**
     * Генерация карты сайта, для этого раздела
     *
     * @param $changefreq
     * @param $priority
     * @return string
     */
    public static function sitemapGenerate($changefreq, $priority): string
    {
        $xml = "";

        $list = self::query()->active()->get();

        foreach ($list as $item) {
            $xml .= app(Sitemap::class)->row($item->frontLink(), $changefreq, $priority, true, $item);
        }

        return $xml;
    }
}
