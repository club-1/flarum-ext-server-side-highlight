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

namespace Club1\ServerSideHighlight\Tests\unit;

use Club1\ServerSideHighlight\Frontend\Content;
use Flarum\Frontend\Document;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Testing\unit\TestCase;
use Mockery as m;
use Mockery\MockInterface;

class ContentTest extends TestCase
{
    /** @var SettingsRepositoryInterface&MockInterface */
    protected $settings;

    /** @var UrlGenerator&MockInterface */
    protected $url;

    /** @var Document&MockInterface */
    protected $doc;

    public function setUp(): void
    {
        parent::setUp();
        $this->settings = m::mock(SettingsRepositoryInterface::class);
        $this->url = m::mock(UrlGenerator::class);
        $this->doc = m::mock(Document::class);
    }

    /**
     * @dataProvider basicProvider
     */
    public function testBasic(bool $dark, string $css): void
    {
        $jsPath = Content::PATH . '/highlight.min.js';
        $cssPath = Content::PATH . "/$css.min.css";
        $this->url->shouldReceive('to->path')->once()->with($jsPath)->andReturn($jsPath);
        $this->url->shouldReceive('to->path')->once()->with($cssPath)->andReturn($cssPath);
        $this->settings->shouldReceive('get')->withSomeOfArgs('theme_dark_mode')->andReturn($dark);
        $theme = $dark ? 'dark' : 'light';
        $this->settings->shouldReceive('get')->withSomeOfArgs("club-1-server-side-highlight.{$theme}_theme_highlight_theme")->andReturn($css);
        $this->settings->shouldReceive('get')->withSomeOfArgs("club-1-server-side-highlight.{$theme}_theme_bg_color")->andReturn('#000');
        $this->settings->shouldReceive('get')->withSomeOfArgs("club-1-server-side-highlight.{$theme}_theme_text_color")->andReturn('#fff');

        $content = new Content($this->settings, $this->url);
        $content($this->doc);
        $this->assertCount(1, $this->doc->js);
        $this->assertEquals($jsPath, $this->doc->js[0]);
        $this->assertCount(1, $this->doc->css);
        $this->assertEquals($cssPath, $this->doc->css[0]);
        $this->assertCount(1, $this->doc->head);
        $this->assertStringContainsString('--codeblock-bg: #000;', $this->doc->head[0]);
        $this->assertStringContainsString('--codeblock-color: #fff;', $this->doc->head[0]);
    }

    public function basicProvider(): array
    {
        return [
            [true, 'github-dark'],
            [false, 'github'],
        ];
    }
}
