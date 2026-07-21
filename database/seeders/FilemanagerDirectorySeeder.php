<?php

namespace Database\Seeders;

use App\Models\Filemanager\Directory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilemanagerDirectorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Directory::create([
            'title' => 'Root',
            'children' => [
                [
                    'title' => 'Уровень 1: Документы',
                    'children' => [
                        [
                            'title' => 'Уровень 2: Проекты',
                            'children' => [
                                [
                                    'title' => 'Уровень 3: WebX',
                                    'children' => [
                                        ['title' => 'Уровень 4: Спецификации'],
                                        ['title' => 'Уровень 4: Дизайн-система']
                                    ]
                                ]
                            ]
                        ],
                        ['title' => 'Уровень 2: Личное'],
                        ['title' => 'Уровень 2: Личное 2']
                    ]
                ],
                ['title' => 'Уровень 1: Загрузки']
            ]
        ]);
    }
}
