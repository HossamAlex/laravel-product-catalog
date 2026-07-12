<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'اللون' => [
                'أسود',
                'أبيض',
                'بيج',
                'بني',
                'ذهبي',
                'فضي',
                'وردي',
                'أحمر',
                'أزرق',
                'أخضر',
                'خاكي',
            ],
            'المقاس' => [
                '36',
                '37',
                '38',
                '39',
                '40',
                '41',
                '42',
                'S',
                'M',
                'L',
                'XL',
            ],
            'الخامة' => [
                'جلد',
                'جلد صناعي',
                'قماش',
                'شمواه',
                'كانفاس',
            ],
            'المناسبة' => [
                'يومي',
                'دوام',
                'حفلات',
                'زفاف',
                'سهرة',
            ],
        ];

        foreach ($attributes as $title => $values) {
            $attribute = Attribute::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'type' => 'select',
                    'sort' => 0,
                    'status' => true,
                ]
            );

            foreach ($values as $index => $value) {
                AttributeValue::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'slug' => Str::slug($value),
                    ],
                    [
                        'title' => $value,
                        'sort' => $index,
                        'status' => true,
                    ]
                );
            }
        }
    }
}