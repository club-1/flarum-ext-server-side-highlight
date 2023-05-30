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

namespace Club1\ServerSideHighlight\Frontend;

use Club1\ServerSideHighlight\Consts;
use Flarum\Frontend\Document;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;

class Content
{
    /** @var SettingsRepositoryInterface */
    protected $settings;

    /** @var Cloud */
    protected $assetsDisk;

    public function __construct(SettingsRepositoryInterface $settings, Factory $filesystemFactory)
    {
        $this->settings = $settings;
        $this->assetsDisk = $filesystemFactory->disk('flarum-assets');
    }

    public function getAssetUrl(string $assetPath): string
    {
        return $this->assetsDisk->url(Consts::ASSETS_PATH . $assetPath);
    }

    public function __invoke(Document $document): void {
        $document->js[] = $this->getAssetUrl('highlight.min.js');
        if ($this->settings->get('theme_dark_mode', false)) {
            $theme = $this->settings->get('club-1-server-side-highlight.dark_theme_highlight_theme');
            $bgColor = $this->settings->get('club-1-server-side-highlight.dark_theme_bg_color');
            $textColor = $this->settings->get('club-1-server-side-highlight.dark_theme_text_color');
        } else {
            $theme = $this->settings->get('club-1-server-side-highlight.light_theme_highlight_theme');
            $bgColor = $this->settings->get('club-1-server-side-highlight.light_theme_bg_color');
            $textColor = $this->settings->get('club-1-server-side-highlight.light_theme_text_color');
        }
        $document->css[] = $this->getAssetUrl("$theme.min.css");
        $document->head[] = "<style>
:root {
  --codeblock-bg: $bgColor;
  --codeblock-color: $textColor;
}
</style>";
    }
}
