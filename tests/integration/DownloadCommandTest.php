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

use Club1\ServerSideHighlight\Exception\InvalidArgumentException;
use Club1\ServerSideHighlight\Exception\IOException;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Testing\integration\ConsoleTestCase;

class SphinxAddCommandTest extends ConsoleTestCase
{
    const THEME_DIR = __DIR__ . "/tmp/public/assets/extensions/club-1-server-side-highlight";

    /** @var string|null */
    protected $themePath;

    public function setUp(): void
    {
        parent::setUp();
        $this->themePath = null;
        $this->extension('club-1-server-side-highlight');
        $this->prepareDatabase([]);
    }

    public function tearDown(): void
    {
        chmod(self::THEME_DIR, 0755);
        if ($this->themePath != null) {
            @unlink($this->themePath);
        }
    }

    protected function getThemePath(string $theme): string
    {
        return self::THEME_DIR . "/$theme.min.css";
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid(array $input, string $expected): void
    {
        $this->themePath = $this->getThemePath($input['name']);
        $input = array_merge(['command' => 'highlight:download'], $input);
        $this->assertFileDoesNotExist($this->themePath);
        $this->runCommand($input);
        $settings = $this->app()->getContainer()->make(SettingsRepositoryInterface::class);
        $setting = $settings->get('club-1-server-side-highlight.available_themes');
        $this->assertStringContainsString($expected, $setting);
        $this->assertFileExists($this->themePath);
    }

    public function validProvider(): array
    {
        return [
            "basic" => [['name' => 'arta'], '"arta":"Arta"'],
            "depth 1" => [['name' => 'base16/atlas'], '"base16/atlas":"Base16 / Atlas"'],
            "composed" => [['name' => 'arduino-light'], '"arduino-light":"Arduino Light"'],
        ];
    }

    /**
     * @dataProvider exceptionsProvider
     * @param class-string<Throwable> $class
     */
    public function testExceptions(array $input, string $class, string $regex, int $dirPerms = 0755): void
    {
        $input = array_merge(['command' => 'highlight:download'], $input);
        $this->expectException($class);
        $this->expectExceptionMessageMatches($regex);
        $this->console()->setCatchExceptions(false);
        chmod(self::THEME_DIR, $dirPerms);

        $this->runCommand($input);
        $this->assertFileDoesNotExist($this->getThemePath($input['name']));
    }

    public function exceptionsProvider(): array
    {
        return [
            "remote not exists" => [['name' => 'artaz'], InvalidArgumentException::class, '/^Could not download theme artaz\.min\.css:.*404/'],
            "dir not writable" => [['name' => 'agate'], IOException::class, '/Could not write theme to assets:.*Permission denied/', 0544],
        ];
    }
}
