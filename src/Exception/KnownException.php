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

namespace Club1\ServerSideHighlight\Exception;

use Throwable;

interface KnownException extends Throwable
{
    /**
     * Determine the exception's type.
     *
     * This should be a short, precise identifier for the error that can be
     * exposed to users as an error code. Furthermore, it can be used to find
     * appropriate error messages in translations or views to render pretty
     * error pages.
     *
     * Different exception classes are allowed to return the same status code,
     * e.g. when they have similar semantic meaning to the end user, but are
     * thrown by different subsystems.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Determine the HTTP status code to use with this exception.
     *
     * @return int The HTTP status code to use.
     */
    public function getStatusCode(): int;
}
