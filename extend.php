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

use Club1\ServerSideHighlight\Console\DownloadCommand;
use Club1\ServerSideHighlight\Controller\PreviewController;
use Club1\ServerSideHighlight\Formatter\Configurator;
use Club1\ServerSideHighlight\Formatter\Renderer;
use Club1\ServerSideHighlight\Frontend\Content;
use Club1\ServerSideHighlight\Serializer\HighlightThemeSerializer;
use Flarum\Extend;

return [
    (new Extend\Formatter)
        ->configure(Configurator::class)
        ->render(Renderer::class),

    (new Extend\Frontend('forum'))
        ->content(Content::class)
        ->js(__DIR__.'/js/forum.js')
        ->css(__DIR__.'/css/forum.css'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/admin.js')
        ->css(__DIR__.'/css/admin.css'),

    (new Extend\Routes('api'))
        ->get('/highlight-preview', 'club-1-server-side-highlight.preview', PreviewController::class),

    (new Extend\Settings())
        ->default('club-1-server-side-highlight.light_theme_bg_color', '#f7f7f7')
        ->default('club-1-server-side-highlight.light_theme_text_color', '#000000')
        ->default('club-1-server-side-highlight.light_theme_highlight_theme', 'github')
        ->default('club-1-server-side-highlight.dark_theme_bg_color', '#0e1115')
        ->default('club-1-server-side-highlight.dark_theme_text_color', '#ffffff')
        ->default('club-1-server-side-highlight.dark_theme_highlight_theme', 'github-dark')
        ->serializeToForum('lightThemeBgColor', 'club-1-server-side-highlight.light_theme_bg_color')
        ->serializeToForum('lightThemeTextColor', 'club-1-server-side-highlight.light_theme_text_color')
        ->serializeToForum('lightThemeHighlightTheme', 'club-1-server-side-highlight.light_theme_highlight_theme', HighlightThemeSerializer::class)
        ->serializeToForum('darkThemeBgColor', 'club-1-server-side-highlight.dark_theme_bg_color')
        ->serializeToForum('darkThemeTextColor', 'club-1-server-side-highlight.dark_theme_text_color')
        ->serializeToForum('darkThemeHighlightTheme', 'club-1-server-side-highlight.dark_theme_highlight_theme', HighlightThemeSerializer::class),

    (new Extend\Console())
        ->command(DownloadCommand::class),

    (new Extend\View)
        ->namespace('club-1.server-side-highlight', __DIR__.'/views'),

    new Extend\Locales(__DIR__.'/locale'),
];
