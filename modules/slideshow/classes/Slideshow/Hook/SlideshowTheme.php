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
class Slideshow_Hook_SlideshowTheme {
  static function page_bottom($theme) {
    // @TODO: fix dependencies so this can go in head instead of page_bottom.
    $buf = "";

    // Include album-specific JS (*not* grouped with other scripts)
    $id = $theme->item->is_album() ? $theme->item()->id : $theme->item()->parent_id;
    $buf .= HTML::script("slideshow/js/$id", array(), null, true);

    // Include Galleria JS (grouped with other scripts)
    $ss_theme = Module::get_var("slideshow", "theme", "classic");
    $buf .= $theme->script("galleria/galleria.min.js");
    $buf .= $theme->script("galleria/themes/$ss_theme/galleria.$ss_theme.min.js");
    $buf .= $theme->css("galleria/themes/$ss_theme/galleria.$ss_theme.css");

    return $buf;
  }
}
