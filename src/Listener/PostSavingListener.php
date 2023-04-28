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

namespace Club1\ServerSideHighlight\Listener;

use Club1\ServerSideHighlight\Formatter\Utils;
use DOMElement;
use Flarum\Post\CommentPost;
use Flarum\Post\Event\Saving;
use Highlight\Highlighter;
use Illuminate\Contracts\Cache\Repository;

class PostSavingListener
{
    /** @var Repository */
    protected $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Configure rendering for highlighting.
     *
     * Try to get the highlighted code from the cache, then if not found,
     * clean each CODE xml blocks from the elements we don't want, before
     * applying the highlighting on their textContent and adding it to
     * the cache and setting their 'higlighted' property.
     */
    public function handle(Saving $event)
    {
        if (!($event->post instanceof CommentPost) || empty($event->data['attributes']['content'])) {
            return;
        }
        $xml = $event->post->parsed_content;
        if (strpos($xml, 'CODE') === false) {
            return;
        }
        $dom = Utils::loadXML($xml);
        foreach ($dom->getElementsByTagName('CODE') as $code) {
            assert($code instanceof DOMElement);
            $lang = $code->getAttribute('lang');
            if ($lang == '') {
                continue;
            }
            // Remove start and end elements
            $copy = clone $code;
            $copy->removeChild($copy->getElementsByTagName('s')->item(0));
            $copy->removeChild($copy->getElementsByTagName('e')->item(0));
            // Remove ignored elements
            $ignored = $copy->getElementsByTagName('i');
            $count = $ignored->length;
            for ($i = 0; $i < $count; $i++) {
                // Always remove first child as the iterator is moved by removeChild
                $copy->removeChild($ignored->item(0));
            }
            $text = $copy->textContent;
            $hash = md5($text);
            $key = $lang . $hash;
            $highlighted = $this->cache->get($key);
            if ($highlighted == null) {
                $hl = new Highlighter();
                try {
                    $highlighted = $hl->highlight($lang, $text)->value;
                } catch (\Throwable $_) {
                    $highlighted = '';
                }
                $this->cache->put($key, $highlighted);
            }
            $code->setAttribute('highlighted', $highlighted);
        }
        $event->post->parsed_content = Utils::saveXML($dom);
    }
}

