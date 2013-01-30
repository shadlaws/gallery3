<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Image extends Image_Core {
  /**
   * We added two functions here to add interlace and strip.  We handle it similar to
   * quality, in that they are added to the actions array but removed before running execute.
   */

  /**
   * Specify whether or not to make image interlaced (a.k.a. progressive).
   *
   * @param   boolean  true to turn on, false to turn off
   * @return  object
   */
  public function interlace($interlace) {
    $this->actions['interlace'] = $interlace;
    return $this;
  }

  /**
   * Strip all metadata from image.
   *
   * @param   boolean  true to strip metadata, false to take no action
   * @return  object
   */
  public function strip($strip) {
    if ($strip) {
      $this->actions['strip'] = true;
    }
    return $this;
  }
}