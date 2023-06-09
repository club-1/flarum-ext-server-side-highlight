<?php

/*
 * This file is part of club-1/flarum-ext-server-side-highlight.
 *
 * Copyright (c) 2023 Nicolas Peugnet <nicolas@club1.fr>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Club1\ServerSideHighlight\Console;

use Club1\ServerSideHighlight\Consts;
use ErrorException;
use Flarum\Console\AbstractCommand;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use Symfony\Component\Console\Input\InputArgument;

class DownloadCommand extends AbstractCommand
{
    protected const HIGHLIGHTJS_STYLES_PATH = 'https://github.com/highlightjs/cdn-release/raw/11-stable/build/styles/';

    /** @var Cloud */
    protected $assetsDisk;

    /** @var SettingsRepositoryInterface */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings, Factory $filesystemFactory)
    {
        $this->assetsDisk = $filesystemFactory->disk('flarum-assets');
        $this->settings = $settings;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('highlight:download')
            ->setDescription('Download a code syntax highlight theme from highlight.js releases')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the theme');
    }

    protected function fire(): void
    {
        set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        $name = $this->input->getArgument('name') . '.min.css';

        $this->info("Downloading highlight theme: $name");
        $localFile = Consts::ASSETS_PATH . $name;
        $remoteFile = self::HIGHLIGHTJS_STYLES_PATH . $name;
        try {
            $remote = fopen($remoteFile, 'r');
            assert(is_resource($remote));
            $this->assetsDisk->put($localFile, $remote);
        } catch (\Throwable $t) {
            $this->assetsDisk->delete($localFile);
            throw $t;
        }

        $this->info("Updating available highlight themes...");
        $allFiles = $this->assetsDisk->allFiles(Consts::ASSETS_PATH);
        $files = array_filter($allFiles, function (string $file) {
            return substr($file, -8) == '.min.css';
        });
        $names = array_map(function (string $file) {
            return substr($file, strlen(Consts::ASSETS_PATH), -8);
        }, $files);
        $displayNames = array_map(function (string $name) {
            return ucwords(strtr($name, ['-' => ' ', '/' => ' / ']));
        }, $names);
        $themes = array_combine($names, $displayNames);
        $json = json_encode($themes, JSON_UNESCAPED_SLASHES);
        $this->settings->set('club-1-server-side-highlight.available_themes', $json);
    }
}
