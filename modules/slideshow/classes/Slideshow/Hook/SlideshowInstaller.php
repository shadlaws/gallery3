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
class Slideshow_Hook_SlideshowInstaller {
  static function install() {
    Module::set_var("slideshow", "theme", "classic");
    Module::set_var("slideshow", "size", Module::get_var("gallery", "resize_size"));
  }

  static function upgrade($version) {
    if ($version == 1) {
      Module::set_var("slideshow", "max_scale", 0);
      Module::set_version("slideshow", $version = 2);
    }

    if ($version == 2) {
      // In v1-v2, we used the Flash-based Cooliris for the slideshow.
      // Clear remnants of the old module.
      Module::clear_var("slideshow", "max_scale");
      SiteStatus::clear("slideshow_needs_rss");

      // In v3, we now use the JS-based Galleria for the slideshow.
      Module::set_var("slideshow", "theme", "classic");
      Module::set_var("slideshow", "size", Module::get_var("gallery", "resize_size"));
      Module::set_version("slideshow", $version = 3);
    }
  }
}
