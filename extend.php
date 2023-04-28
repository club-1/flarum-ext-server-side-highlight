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

use Club1\ServerSideHighlight\Formatter\Configurator;
use Club1\ServerSideHighlight\Frontend\Content;
use Club1\ServerSideHighlight\Listener\PostSavingListener;
use Flarum\Extend;
use Flarum\Post;

return [
    (new Extend\Formatter)
        ->configure(Configurator::class),

    (new Extend\Event)
        ->listen(Post\Event\Saving::class, PostSavingListener::class),

    (new Extend\Frontend('forum'))
        ->content(Content::class),

];
